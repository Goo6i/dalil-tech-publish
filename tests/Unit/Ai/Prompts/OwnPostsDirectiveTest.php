<?php

declare(strict_types=1);

test('the own-posts section renders the samples and asks to match, not copy', function () {
    $out = view('prompts.post_content._own_posts', [
        'own_posts' => ['قهوة الصباح جاهزة', 'خصم اليوم على الكيك'],
    ])->render();

    expect($out)->toContain("brand's own recent posts")
        ->and($out)->toContain('قهوة الصباح جاهزة')
        ->and($out)->toContain('Do NOT copy');

    $empty = view('prompts.post_content._own_posts', ['own_posts' => []])->render();
    expect(trim($empty))->toBe('');
});
