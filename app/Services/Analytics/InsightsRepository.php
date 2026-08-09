<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use Illuminate\Support\Facades\DB;

class InsightsRepository
{
    /**
     * Per-video scorecard (one row per video: engagement, trajectory, class, etc.)
     * for the given platform/username pairs.
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     * @return list<array<string, mixed>>
     */
    public function scorecard(array $pairs): array
    {
        if ($pairs === []) {
            return [];
        }

        $query = DB::connection('analytics')
            ->table('video_scorecard')
            ->orderByDesc('posted_at');

        return $this->wherePairs($query, $pairs)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Daily follower momentum for the given pairs, over the trailing $days days.
     *
     * Note: account_momentum has no platform column (it is grouped by username
     * only), so this filters by username alone rather than the full pair.
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     * @return list<array<string, mixed>>
     */
    public function accountMomentum(array $pairs, int $days = 30): array
    {
        if ($pairs === []) {
            return [];
        }

        $usernames = array_values(array_unique(array_column($pairs, 'username')));

        return DB::connection('analytics')
            ->table('account_momentum')
            ->whereIn('username', $usernames)
            ->where('day', '>=', now()->subDays($days))
            ->orderBy('username')
            ->orderBy('day')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function dataQuality(): array
    {
        return DB::connection('analytics')
            ->table('data_quality')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Most recent alerts_sent rows, joined to video_latest for title/platform/username
     * so they can be pair-filtered and displayed without a second lookup.
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     * @return list<array<string, mixed>>
     */
    public function recentAlerts(array $pairs, int $limit = 10): array
    {
        if ($pairs === []) {
            return [];
        }

        $query = DB::connection('analytics')
            ->table('alerts_sent as a')
            ->join('video_latest as v', 'v.video_id', '=', 'a.video_id')
            ->select([
                'a.video_id',
                'a.alert_type',
                'a.sent_at',
                'v.title',
                'v.platform',
                'v.username',
            ])
            ->orderByDesc('a.sent_at')
            ->limit($limit);

        return $this->wherePairs($query, $pairs)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Single video's scorecard row, scoped to the given pairs (so a video that
     * doesn't belong to any of the workspace's connected accounts resolves to
     * null rather than leaking cross-workspace data).
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     */
    public function video(array $pairs, string $videoId): ?array
    {
        if ($pairs === []) {
            return null;
        }

        $query = DB::connection('analytics')
            ->table('video_scorecard')
            ->where('video_id', $videoId);

        $row = $this->wherePairs($query, $pairs)->first();

        return $row ? (array) $row : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function trajectorySeries(string $videoId): array
    {
        return DB::connection('analytics')
            ->table('video_deltas')
            ->select(['captured_at', 'age_h', 'd_views', 'views_per_hour'])
            ->where('video_id', $videoId)
            ->orderBy('captured_at')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function atAge(string $videoId): array
    {
        return DB::connection('analytics')
            ->table('video_at_age')
            ->where('video_id', $videoId)
            ->orderBy('anchor_h')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    public function expectation(string $videoId): ?array
    {
        $row = DB::connection('analytics')
            ->table('video_expectation')
            ->where('video_id', $videoId)
            ->first();

        return $row ? (array) $row : null;
    }

    /**
     * Best-time-to-post cells (daypart x daytype) for the given pairs.
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     * @return list<array<string, mixed>>
     */
    public function bestTime(array $pairs): array
    {
        if ($pairs === []) {
            return [];
        }

        $query = DB::connection('analytics')
            ->table('best_time')
            ->orderBy('platform')
            ->orderBy('username')
            ->orderBy('slot_rank');

        return $this->wherePairs($query, $pairs)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /**
     * Best (slot_rank = 1) posting-time cell for a single pair, if one exists.
     */
    public function bestTimeHint(string $platform, string $username): ?array
    {
        $row = DB::connection('analytics')
            ->table('best_time')
            ->where('platform', $platform)
            ->where('username', $username)
            ->where('slot_rank', 1)
            ->first();

        return $row ? (array) $row : null;
    }

    /**
     * Sum of positive engagement deltas over the trailing 7 days, for the given pairs.
     *
     * @param  list<array{platform: string, username: string}>  $pairs
     */
    public function receivedLast7d(array $pairs): array
    {
        if ($pairs === []) {
            return [];
        }

        $query = DB::connection('analytics')
            ->table('video_deltas')
            ->selectRaw(
                'sum(greatest(d_views, 0)) as views, '.
                'sum(greatest(d_likes, 0)) as likes, '.
                'sum(greatest(d_comments, 0)) as comments, '.
                'sum(greatest(d_shares, 0)) as shares'
            )
            ->whereNotNull('d_views')
            ->whereRaw("captured_at >= now() - interval '7 days'");

        $row = $this->wherePairs($query, $pairs)->first();

        return $row ? (array) $row : [];
    }

    /**
     * @param  list<array{platform: string, username: string}>  $pairs
     */
    private function wherePairs($query, array $pairs)
    {
        $placeholders = implode(',', array_fill(0, count($pairs), '(?, ?)'));
        $bindings = [];
        foreach ($pairs as $p) {
            $bindings[] = $p['platform'];
            $bindings[] = $p['username'];
        }

        return $query->whereRaw("(platform, username) IN ($placeholders)", $bindings);
    }
}
