<?php

declare(strict_types=1);

use App\Enums\UserWorkspace\Role;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Workspace;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

// Feature-level stand-in for the browser scenario in tests/Browser/InsightsTest.php:
// this environment's test image cannot launch a browser (Playwright is
// outdated / no browser binaries installed here, see task notes), so this
// asserts the same synthetic-data rendering contract at the HTTP+Inertia
// layer instead, which does run locally. It does not replace the browser
// test -- both files ship together.
//
// Mirrors the Task 4 pattern: synthetic analytics rows (usernames/video_ids
// prefixed `pest-`) inserted on the `analytics` connection (-> analytics_test)
// and cleaned up in afterEach. Skips the whole file when the analytics
// connection is unreachable, so upstream CI (no analytics DB) stays green.
beforeEach(function () {
    try {
        DB::connection('analytics')->select('select 1');
    } catch (\Throwable $e) {
        $this->markTestSkipped('analytics connection unreachable: '.$e->getMessage());
    }

    $this->user = User::factory()->create();
    $this->workspace = Workspace::factory()->create(['user_id' => $this->user->id]);
    $this->workspace->members()->attach($this->user->id, ['role' => Role::Member->value]);
    $this->user->update(['current_workspace_id' => $this->workspace->id]);

    // Inserts two video_snapshots rows for one synthetic video: one at
    // $postedAt (age 0h) and one at $postedAt + 24h (within video_at_age's
    // +-1.5h anchor tolerance), so video_deltas produces a scored delta row
    // (non-null views_per_hour) for the trajectory series and
    // video_scorecard.views_24h is populated.
    $this->insertVideo = function (string $platform, string $username, string $videoId, CarbonInterface $postedAt, int $views = 1000): void {
        DB::connection('analytics')->table('video_snapshots')->insert([
            [
                'platform' => $platform,
                'username' => $username,
                'video_id' => $videoId,
                'title' => 'Pest render video',
                'posted_at' => $postedAt->toIso8601String(),
                'captured_at' => $postedAt->toIso8601String(),
                'views' => $views,
                'likes' => 10,
                'comments' => 2,
                'shares' => 1,
                'duration_sec' => 30,
            ],
            [
                'platform' => $platform,
                'username' => $username,
                'video_id' => $videoId,
                'title' => 'Pest render video',
                'posted_at' => $postedAt->toIso8601String(),
                'captured_at' => $postedAt->copy()->addHours(24)->toIso8601String(),
                'views' => $views + 500,
                'likes' => 25,
                'comments' => 5,
                'shares' => 3,
                'duration_sec' => 30,
            ],
        ]);
    };
});

afterEach(function () {
    try {
        DB::connection('analytics')->select('select 1');
    } catch (\Throwable) {
        return;
    }

    DB::connection('analytics')->table('video_snapshots')
        ->where('username', 'like', 'pest-%')
        ->orWhere('video_id', 'like', 'pest-%')
        ->delete();

    DB::connection('analytics')->table('account_snapshots')
        ->where('username', 'like', 'pest-%')
        ->delete();
});

test('insights overview renders the Index component with the video title and views in the scorecard', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-render-acct',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-render-acct', 'pest-render-vid', now()->subDays(3));

    $response = $this->actingAs($this->user)->get(route('app.insights'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('analytics/insights/Index', false)
        ->has('scorecard', 1)
        ->where('scorecard.0.video_id', 'pest-render-vid')
        ->where('scorecard.0.title', 'Pest render video')
        ->has('scorecard.0.views')
        ->whereNot('scorecard.0.views', null)
    );
});

test('video drilldown renders the Video component with a scored trajectory point', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-render-video-acct',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-render-video-acct', 'pest-render-video-vid', now()->subDays(5));

    $response = $this->actingAs($this->user)->get(route('app.insights.video', ['videoId' => 'pest-render-video-vid']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('analytics/insights/Video', false)
        ->where('video.video_id', 'pest-render-video-vid')
        ->where('video.title', 'Pest render video')
        // Two video_deltas rows: the first (age 0h) has a null views_per_hour
        // (no prior snapshot to diff against), the second (age 24h) is scored.
        ->has('trajectory', 2)
        ->where('trajectory.0.views_per_hour', null)
        ->whereNot('trajectory.1.views_per_hour', null)
    );
});
