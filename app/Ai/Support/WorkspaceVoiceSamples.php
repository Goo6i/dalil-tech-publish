<?php

declare(strict_types=1);

namespace App\Ai\Support;

use App\Models\Workspace;
use Illuminate\Support\Str;

final class WorkspaceVoiceSamples
{
    /**
     * The workspace's own recent published post copy, used to anchor the
     * brand's real voice in generation prompts. Empty when there is no history.
     *
     * @return array<int, string>
     */
    public static function for(Workspace $workspace, int $limit = 6, int $maxChars = 400): array
    {
        if (! $workspace->exists) {
            return [];
        }

        return $workspace->posts()
            ->published()
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->latest('published_at')
            ->limit($limit)
            ->pluck('content')
            ->map(fn ($c) => Str::limit(trim((string) $c), $maxChars))
            ->filter(fn ($c) => $c !== '')
            ->values()
            ->all();
    }
}
