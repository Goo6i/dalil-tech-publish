<?php

declare(strict_types=1);

use App\Ai\Support\WorkspaceVoiceSamples;
use App\Enums\Post\Status as PostStatus;
use App\Models\Post;
use App\Models\Workspace;

test('it returns recent published post copy, newest first, ignoring drafts and empties', function () {
    $workspace = Workspace::factory()->create();

    Post::factory()->for($workspace)->create([
        'status' => PostStatus::Published, 'published_at' => now()->subDay(), 'content' => 'قهوة الصباح جاهزة، تفضلوا',
    ]);
    Post::factory()->for($workspace)->create([
        'status' => PostStatus::Published, 'published_at' => now(), 'content' => 'خصم اليوم على الكيك',
    ]);
    Post::factory()->for($workspace)->create([
        'status' => PostStatus::Draft, 'content' => 'مسودة ما تنحسب',
    ]);
    Post::factory()->for($workspace)->create([
        'status' => PostStatus::Published, 'published_at' => now(), 'content' => '',
    ]);

    $samples = WorkspaceVoiceSamples::for($workspace);

    expect($samples)->toHaveCount(2)
        ->and($samples[0])->toBe('خصم اليوم على الكيك')
        ->and($samples)->toContain('قهوة الصباح جاهزة، تفضلوا')
        ->and($samples)->not->toContain('مسودة ما تنحسب');
});

test('an unsaved workspace yields no samples', function () {
    expect(WorkspaceVoiceSamples::for(new Workspace(['content_language' => 'ar'])))->toBe([]);
});
