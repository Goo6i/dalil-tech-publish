<?php

declare(strict_types=1);

use App\Enums\UserWorkspace\Role;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

// HTTP-level contract tests for app/Http/Controllers/App/InsightsController.php.
// Synthetic analytics rows (usernames/video_ids prefixed `pest-`) are inserted
// on the `analytics` connection (-> analytics_test) and cleaned up in
// afterEach. Skips the whole file when the analytics connection is
// unreachable, so upstream CI (no analytics DB) stays green.
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
    // +-1.5h anchor tolerance), so video_scorecard.views_24h and the
    // best_time view's n_scored both pick it up.
    $this->insertVideo = function (string $platform, string $username, string $videoId, CarbonInterface $postedAt, int $views = 1000): void {
        DB::connection('analytics')->table('video_snapshots')->insert([
            [
                'platform' => $platform,
                'username' => $username,
                'video_id' => $videoId,
                'title' => 'Pest synthetic video',
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
                'title' => 'Pest synthetic video',
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

    $this->insertAccountSnapshot = function (string $platform, string $username, int $followers = 100): void {
        DB::connection('analytics')->table('account_snapshots')->insert([
            'platform' => $platform,
            'username' => $username,
            'captured_at' => now()->toIso8601String(),
            'followers' => $followers,
            'following' => 10,
            'likes' => 500,
            'video_count' => 5,
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

test('insights index requires authentication', function () {
    $response = $this->get(route('app.insights'));

    $response->assertRedirect(route('login'));
});

test('insights index renders a scorecard scoped to the workspace account', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-index-acct',
        'is_active' => true,
    ]);

    ($this->insertAccountSnapshot)('tiktok', 'pest-ctrl-index-acct');
    ($this->insertVideo)('tiktok', 'pest-ctrl-index-acct', 'pest-ctrl-index-vid', now()->subDays(3));

    // An account not connected to this workspace must never leak into its scorecard.
    ($this->insertVideo)('tiktok', 'pest-ctrl-other-acct', 'pest-ctrl-other-vid', now()->subDays(3));

    $response = $this->actingAs($this->user)->get(route('app.insights'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('analytics/insights/Index', false)
        ->has('accounts', 1)
        ->has('scorecard', 1)
        ->where('scorecard.0.video_id', 'pest-ctrl-index-vid')
        ->where('scorecard.0.username', 'pest-ctrl-index-acct')
    );
});

test('insights index renders cleanly for a workspace with no connected accounts', function () {
    $response = $this->actingAs($this->user)->get(route('app.insights'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('analytics/insights/Index', false)
        ->has('accounts', 0)
        ->has('scorecard', 0)
        ->has('momentum', 0)
        ->has('alerts', 0)
    );
});

test('video route 404s for a video belonging to a pair outside the workspace', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-video-owner',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-ctrl-video-foreign', 'pest-ctrl-video-foreign-vid', now()->subDays(5));

    $response = $this->actingAs($this->user)->get(route('app.insights.video', ['videoId' => 'pest-ctrl-video-foreign-vid']));

    $response->assertNotFound();
});

test('video route renders the video belonging to the workspace', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-video-owner',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-ctrl-video-owner', 'pest-ctrl-video-owned-vid', now()->subDays(5));

    $response = $this->actingAs($this->user)->get(route('app.insights.video', ['videoId' => 'pest-ctrl-video-owned-vid']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('analytics/insights/Video', false)
        ->where('video.video_id', 'pest-ctrl-video-owned-vid')
    );
});

test('best time hint returns null for a pair with no ranked slot yet', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-hint-cold',
        'is_active' => true,
    ]);

    ($this->insertVideo)('tiktok', 'pest-ctrl-hint-cold', 'pest-ctrl-hint-cold-vid', now()->subDays(10));

    $response = $this->actingAs($this->user)->getJson(route('app.insights.hint', [
        'platform' => 'tiktok',
        'username' => 'pest-ctrl-hint-cold',
    ]));

    $response->assertOk();
    $response->assertJson(['hint' => null]);
});

test('best time hint returns a ranked slot once 3+ same-daypart videos are scored', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-hint-hot',
        'is_active' => true,
    ]);

    $postedAt = Carbon::parse('monday this week', 'Asia/Riyadh')->subWeeks(3)->setTime(9, 0);

    foreach (range(1, 3) as $i) {
        ($this->insertVideo)('tiktok', 'pest-ctrl-hint-hot', "pest-ctrl-hint-hot-vid-{$i}", $postedAt);
    }

    $response = $this->actingAs($this->user)->getJson(route('app.insights.hint', [
        'platform' => 'tiktok',
        'username' => 'pest-ctrl-hint-hot',
    ]));

    $response->assertOk();
    $response->assertJsonPath('hint.daypart', 'morning');
    $response->assertJsonPath('hint.daytype', 'weekday');
    expect($response->json('hint.slot_rank'))->toEqual(1);
});

test('best time hint returns null for a pair not belonging to the workspace', function () {
    SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'username' => 'pest-ctrl-hint-owner',
        'is_active' => true,
    ]);

    $postedAt = Carbon::parse('monday this week', 'Asia/Riyadh')->subWeeks(3)->setTime(9, 0);

    foreach (range(1, 3) as $i) {
        ($this->insertVideo)('tiktok', 'pest-ctrl-hint-notmine', "pest-ctrl-hint-notmine-vid-{$i}", $postedAt);
    }

    $response = $this->actingAs($this->user)->getJson(route('app.insights.hint', [
        'platform' => 'tiktok',
        'username' => 'pest-ctrl-hint-notmine',
    ]));

    $response->assertOk();
    $response->assertJson(['hint' => null]);
});
