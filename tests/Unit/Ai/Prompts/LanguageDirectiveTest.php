<?php

declare(strict_types=1);

test('the language directive commands output by name, with Saudi dialect for Arabic', function () {
    $ar = view('prompts.post_content._language', [
        'language_name' => 'Arabic',
        'language_native' => 'العربية',
    ])->render();

    expect($ar)->toContain('Arabic')
        ->and($ar)->toContain('العربية')
        ->and($ar)->toContain('strict requirement')
        ->and($ar)->toContain('Saudi white dialect');

    $en = view('prompts.post_content._language', [
        'language_name' => 'English',
        'language_native' => 'English',
    ])->render();

    expect($en)->toContain('English')
        ->and($en)->not->toContain('Saudi white dialect');
});
