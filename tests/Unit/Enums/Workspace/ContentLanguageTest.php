<?php

declare(strict_types=1);

use App\Enums\Workspace\ContentLanguage;

test('values exposes every supported content-language code', function () {
    expect(ContentLanguage::values())->toBe(['en', 'ar']);
});

test('default language is English', function () {
    expect(ContentLanguage::DEFAULT)->toBe(ContentLanguage::English);
    expect(ContentLanguage::DEFAULT->value)->toBe('en');
});

test('options pairs each code with its native and English label', function () {
    $options = ContentLanguage::options();

    expect($options)->toHaveCount(count(ContentLanguage::cases()));
    expect($options[0])->toBe(['value' => 'en', 'label' => 'English', 'englishName' => 'English']);
    expect($options)->toContain(['value' => 'ar', 'label' => 'العربية', 'englishName' => 'Arabic']);
});

test('englishName returns a distinct English name for every language', function (ContentLanguage $language, string $expected) {
    expect($language->englishName())->toBe($expected);
})->with([
    [ContentLanguage::English, 'English'],
    [ContentLanguage::Arabic, 'Arabic'],
]);

test('only English resolves to the English name', function () {
    foreach (ContentLanguage::cases() as $language) {
        expect($language->englishName() === 'English')->toBe($language === ContentLanguage::English);
    }
});

test('label returns the native name for every language', function (ContentLanguage $language, string $expected) {
    expect($language->label())->toBe($expected);
})->with([
    [ContentLanguage::English, 'English'],
    [ContentLanguage::Arabic, 'العربية'],
]);

test('direction is rtl only for Arabic', function () {
    expect(ContentLanguage::Arabic->direction())->toBe('rtl');

    foreach (ContentLanguage::cases() as $language) {
        if ($language !== ContentLanguage::Arabic) {
            expect($language->direction())->toBe('ltr');
        }
    }
});

test('fromHtmlLang resolves the two-letter primary subtag', function (string $lang, ?ContentLanguage $expected) {
    expect(ContentLanguage::fromHtmlLang($lang))->toBe($expected);
})->with([
    ['en-US', ContentLanguage::English],
    ['ar', ContentLanguage::Arabic],
    ['ar-SA', ContentLanguage::Arabic],
    // Languages we no longer ship resolve to null.
    ['fr', null],
    ['sv', null],
    ['e', null],
    ['', null],
    // A malformed tag is matched on its whole primary subtag, never a
    // two-letter prefix, "english" must not resolve to English via "en".
    ['english', null],
]);
