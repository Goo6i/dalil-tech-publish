<?php

declare(strict_types=1);

use App\Enums\Workspace\ImageStyle;
use App\Services\Ai\AiImageClient;
use Illuminate\Support\Facades\Http;

$png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

test('generate returns null when keywords are empty', function () use ($png) {
    fakeMiniMaxImage($png);
    expect((new AiImageClient)->generate([], ImageStyle::Cinematic))->toBeNull();
    Http::assertNothingSent();
});

test('generate returns the downloaded image bytes on success', function () use ($png) {
    fakeMiniMaxImage($png);
    expect((new AiImageClient)->generate(['kitchen', 'morning'], ImageStyle::Illustration))->toBe($png);
});

test('generate sends a style-specific prompt', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['mountain hiker'], ImageStyle::Cinematic);
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'Cinematic photograph')
        && str_contains((string) ($r['prompt'] ?? ''), 'mountain hiker'));
});

test('generate maps a portrait orientation to a 9:16 aspect ratio', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, orientation: 'portrait');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation') && ($r['aspect_ratio'] ?? null) === '9:16');
});

test('generate maps a landscape orientation to a 16:9 aspect ratio', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, orientation: 'landscape');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation') && ($r['aspect_ratio'] ?? null) === '16:9');
});

test('generate maps an unknown orientation to a 1:1 aspect ratio', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, orientation: 'whatever');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation') && ($r['aspect_ratio'] ?? null) === '1:1');
});

test('generate appends the Arabic instruction when language is ar', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, language: 'ar');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'Arabic'));
});

test('generate defaults to English when the language is unsupported', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, language: 'sv');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'English'));
});

test('generate appends the brand palette when colours are provided', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Infographic, brandColor: '#facc15', backgroundColor: '#ffffff', textColor: '#0f172a');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'BRAND COLOR PALETTE')
        && str_contains((string) ($r['prompt'] ?? ''), 'golden yellow')
        && str_contains((string) ($r['prompt'] ?? ''), 'charts, bars')
        && str_contains((string) ($r['prompt'] ?? ''), 'off-white')
        && str_contains((string) ($r['prompt'] ?? ''), 'in-scene typography'));
});

test('generate omits the brand palette when no colours are set', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic);
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && ! str_contains((string) ($r['prompt'] ?? ''), 'BRAND COLOR PALETTE'));
});

test('generate includes only valid colours in the palette', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, brandColor: 'not-a-hex', backgroundColor: '#ffffff');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'BRAND COLOR PALETTE')
        && str_contains((string) ($r['prompt'] ?? ''), 'off-white')
        && ! str_contains((string) ($r['prompt'] ?? ''), 'Brand / primary accent'));
});

test('generate appends brand context when a brand description is provided', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, brandDescription: 'a fitness coaching brand for busy professionals');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'Brand context')
        && str_contains((string) ($r['prompt'] ?? ''), 'fitness coaching'));
});

test('generate truncates a brand description longer than 200 chars', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, brandDescription: str_repeat('lorem ipsum ', 50));
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && str_contains((string) ($r['prompt'] ?? ''), 'Brand context')
        && str_contains((string) ($r['prompt'] ?? ''), "\u{2026}"));
});

test('generate omits brand context when the description is only whitespace', function () use ($png) {
    fakeMiniMaxImage($png);
    (new AiImageClient)->generate(['x'], ImageStyle::Cinematic, brandDescription: '   ');
    Http::assertSent(fn ($r) => str_contains((string) $r->url(), 'image_generation')
        && ! str_contains((string) ($r['prompt'] ?? ''), 'Brand context'));
});

test('generate returns null when the image API reports an error', function () {
    failMiniMaxImage();
    expect((new AiImageClient)->generate(['x'], ImageStyle::Cinematic))->toBeNull();
});
