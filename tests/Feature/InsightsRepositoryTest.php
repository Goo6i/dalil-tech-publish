<?php

declare(strict_types=1);

use App\Services\Analytics\InsightsRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

// This suite exercises app/Services/Analytics/InsightsRepository.php directly
// against the `analytics_test` Postgres database (via the `analytics`
// connection). It writes synthetic rows into `video_snapshots` (all
// usernames/video_ids prefixed `pest-`) and relies on the real
// video_scorecard / video_latest / video_at_age / best_time views to derive
// scorecard rows and best-time slots, exactly as production does.
//
// Skips the whole file when the analytics connection is unreachable, so
// upstream CI (no analytics DB) stays green.
beforeEach(function () {
    try {
        DB::connection('analytics')->select('select 1');
    } catch (\Throwable $e) {
        $this->markTestSkipped('analytics connection unreachable: '.$e->getMessage());
    }

    $this->repository = new InsightsRepository;

    // Inserts two video_snapshots rows for one synthetic video: one at
    // $postedAt (age 0h, not within 1.5h of any video_at_age anchor) and one
    // at $postedAt + 24h (within the anchor's +-1.5h tolerance), so
    // video_at_age produces a 24h anchor and video_scorecard.views_24h is
    // populated for it.
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

test('scorecard returns [] without querying when pairs is empty', function () {
    expect($this->repository->scorecard([]))->toBe([]);
});

test('scorecard scopes rows to only the requested pairs', function () {
    ($this->insertVideo)('tiktok', 'pest-repo-acct-1', 'pest-repo-vid-1', now()->subDays(3));
    ($this->insertVideo)('tiktok', 'pest-repo-acct-2', 'pest-repo-vid-2', now()->subDays(3));

    $rows = $this->repository->scorecard([
        ['platform' => 'tiktok', 'username' => 'pest-repo-acct-1'],
    ]);

    expect($rows)->toHaveCount(1);
    expect($rows[0]['video_id'])->toBe('pest-repo-vid-1');
    expect($rows[0]['username'])->toBe('pest-repo-acct-1');
});

test('video returns null for a video belonging to a pair outside the given pairs', function () {
    ($this->insertVideo)('tiktok', 'pest-repo-acct-1', 'pest-repo-vid-1', now()->subDays(3));
    ($this->insertVideo)('tiktok', 'pest-repo-acct-2', 'pest-repo-vid-2', now()->subDays(3));

    $pairs = [['platform' => 'tiktok', 'username' => 'pest-repo-acct-1']];

    expect($this->repository->video($pairs, 'pest-repo-vid-2'))->toBeNull();
    expect($this->repository->video($pairs, 'pest-repo-vid-1'))->not->toBeNull();
});

test('video returns null immediately when pairs is empty', function () {
    ($this->insertVideo)('tiktok', 'pest-repo-acct-1', 'pest-repo-vid-1', now()->subDays(3));

    expect($this->repository->video([], 'pest-repo-vid-1'))->toBeNull();
});

test('bestTimeHint returns null when fewer than 3 scored videos exist for the pair', function () {
    ($this->insertVideo)('tiktok', 'pest-repo-hint-cold', 'pest-repo-hint-cold-1', now()->subDays(10));

    expect($this->repository->bestTimeHint('tiktok', 'pest-repo-hint-cold'))->toBeNull();
});

test('bestTimeHint returns a ranked slot once 3+ same-daypart videos are scored', function () {
    // Monday 09:00 Asia/Riyadh: daypart=morning, daytype=weekday. All three
    // synthetic videos share this exact posted_at so they land in the same
    // best_time cell; each gets a 24h anchor via insertVideo's paired
    // snapshots, so n_scored reaches the >=3 threshold the view requires
    // before it assigns a slot_rank.
    $postedAt = Carbon::parse('monday this week', 'Asia/Riyadh')->subWeeks(3)->setTime(9, 0);

    foreach (range(1, 3) as $i) {
        ($this->insertVideo)('tiktok', 'pest-repo-hint-hot', "pest-repo-hint-hot-{$i}", $postedAt);
    }

    $hint = $this->repository->bestTimeHint('tiktok', 'pest-repo-hint-hot');

    expect($hint)->not->toBeNull();
    expect((int) $hint['slot_rank'])->toBe(1);
    expect($hint['daypart'])->toBe('morning');
    expect($hint['daytype'])->toBe('weekday');
});
