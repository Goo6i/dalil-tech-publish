<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\Workspace;
use App\Services\Analytics\InsightsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class InsightsController extends Controller
{
    public function __construct(private readonly InsightsRepository $insights) {}

    public function index(Request $request): Response
    {
        $workspace = $request->user()->currentWorkspace;

        $this->authorize('view', $workspace);

        $accounts = $this->accounts($workspace);
        $pairs = $this->pairs($accounts);

        return Inertia::render('analytics/insights/Index', [
            'accounts' => $accounts,
            'scorecard' => $this->insights->scorecard($pairs),
            'momentum' => $this->insights->accountMomentum($pairs),
            'received7d' => $this->insights->receivedLast7d($pairs),
            'dataQuality' => $this->insights->dataQuality(),
            'alerts' => $this->insights->recentAlerts($pairs),
        ]);
    }

    public function video(Request $request, string $videoId): Response
    {
        $workspace = $request->user()->currentWorkspace;

        $this->authorize('view', $workspace);

        $pairs = $this->pairs($this->accounts($workspace));

        abort_unless($video = $this->insights->video($pairs, $videoId), 404);

        return Inertia::render('analytics/insights/Video', [
            'video' => $video,
            'trajectory' => $this->insights->trajectorySeries($videoId),
            'atAge' => $this->insights->atAge($videoId),
            'expectation' => $this->insights->expectation($videoId),
        ]);
    }

    public function bestTimes(Request $request): Response
    {
        $workspace = $request->user()->currentWorkspace;

        $this->authorize('view', $workspace);

        $accounts = $this->accounts($workspace);

        return Inertia::render('analytics/insights/BestTimes', [
            'accounts' => $accounts,
            'cells' => $this->insights->bestTime($this->pairs($accounts)),
        ]);
    }

    public function hint(Request $request): JsonResponse
    {
        $workspace = $request->user()->currentWorkspace;

        $this->authorize('view', $workspace);

        $validated = $request->validate([
            'platform' => ['required', 'string'],
            'username' => ['required', 'string'],
        ]);

        $belongsToWorkspace = $workspace->socialAccounts()
            ->where('is_active', true)
            ->where('platform', $validated['platform'])
            ->where('username', $validated['username'])
            ->exists();

        if (! $belongsToWorkspace) {
            return response()->json(['hint' => null]);
        }

        return response()->json([
            'hint' => $this->insights->bestTimeHint($validated['platform'], $validated['username']),
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function accounts(Workspace $workspace): Collection
    {
        return $workspace->socialAccounts()
            ->where('is_active', true)
            ->whereNotNull('username')
            ->get()
            ->map(fn (SocialAccount $account) => [
                'platform' => $account->platform->value,
                'username' => $account->username,
                'display_name' => $account->display_name,
                'avatar_url' => $account->avatar_url,
            ]);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $accounts
     * @return list<array{platform: string, username: string}>
     */
    private function pairs(Collection $accounts): array
    {
        return $accounts
            ->map(fn (array $account) => [
                'platform' => $account['platform'],
                'username' => $account['username'],
            ])
            ->all();
    }
}
