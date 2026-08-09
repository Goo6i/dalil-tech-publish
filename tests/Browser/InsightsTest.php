<?php

declare(strict_types=1);

use App\Enums\UserWorkspace\Role;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Workspace;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

// Browser coverage for the Insights overview and video drilldown
// (app/Http/Controllers/App/InsightsController.php,
// resources/js/pages/analytics/insights/{Index,Video}.vue). Mirrors the
// Task 4 feature-test pattern: synthetic analytics rows (usernames/video_ids
// prefixed `pest-`) inserted on the `analytics` connection (-> analytics_test)
// and cleaned up in afterEach. Skips the whole file when the analytics
// connection is unreachable, so upstream CI (no analytics DB) stays green.
beforeEach(function () {
    try {
        DB::connection('analytics')->select('select 1');
    } catch (\Throwable $e) {
        $this->markTestSkipped('analytics connection unreachable: '.$e->getMessage());
    }

    // Inserts two video_snapshots rows for one synthetic video: one at
    // $postedAt (age 0h) and one at $postedAt + 24h (within video_at_age's
    // +-1.5h anchor tolerance), so video_deltas produces a scored delta row
    // (non-null views_per_hour) for the trajectory chart and
    // video_scorecard.views_24h is populated.
    $this->insertVideo = function (string $platform, string $username, string $videoId, CarbonInterface $postedAt, int $views = 1000): void {
        DB::connection('analytics')->table('video_snapshots')->insert([
            [
                'platform' => $platform,
                'username' => $username,
                'video_id' => $videoId,
                'title' => 'Pest browser video',
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
                'title' => 'Pest browser video',
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

test('insights overview shows the video title and its views, and the drilldown renders the trajectory section', function () {
    $user = User::factory()->create();
    $workspace = Workspace::factory()->create(['user_id' => $user->id]);
    $workspace->members()->attach($user->id, ['role' => Role::Member->value]);
    $user->update(['current_workspace_id' => $workspace->id]);

    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $workspace->id,
        'username' => 'pest-browser-acct',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-browser-acct', 'pest-browser-vid', now()->subDays(3));

    // Read back the scorecard's actual "views" figure (the view resolves it
    // from the underlying snapshots) rather than assuming which snapshot
    // wins, and format it exactly like the frontend's formatNumber()
    // (Number.prototype.toLocaleString('en-US')) so the assertion matches
    // what's rendered.
    $scoredViews = DB::connection('analytics')
        ->table('video_scorecard')
        ->where('video_id', 'pest-browser-vid')
        ->value('views');
    $formattedViews = number_format((int) $scoredViews);

    $this->actingAs($user);

    $page = visit(route('app.insights'));

    $page->assertSee('Pest browser video')
        ->assertSee($formattedViews);

    $page = visit(route('app.insights.video', ['videoId' => 'pest-browser-vid']));

    $page->assertSee('Pest browser video')
        ->assertSee(trans('insights.video.trajectory.title'));
});
