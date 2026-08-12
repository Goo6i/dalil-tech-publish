<?php

declare(strict_types=1);

use App\Support\ArabicText;

test('detects arabic script', function () {
    expect(ArabicText::contains('قهوة مثلجة'))->toBeTrue()
        ->and(ArabicText::contains('iced coffee'))->toBeFalse()
        ->and(ArabicText::contains('18 SAR'))->toBeFalse();
});

test('strips emoji and pictographs, keeps the words', function () {
    expect(ArabicText::stripEmoji('قهوة مثلجة ☕🌟'))->toBe('قهوة مثلجة')
        ->and(ArabicText::stripEmoji('Fresh ✨ coffee 🔥'))->toBe('Fresh coffee');
});

test('shaping produces joined presentation forms, not the logical string', function () {
    $logical = 'قهوة';
    $shaped = ArabicText::shape($logical);

    expect($shaped)->not->toBe($logical)
        ->and(mb_strlen($shaped))->toBeGreaterThan(0);

    // A price keeps its Latin digits intact through shaping + bidi.
    expect(ArabicText::shape('18 ريال'))->toContain('18');
});
