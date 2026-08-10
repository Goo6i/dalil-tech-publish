<?php

declare(strict_types=1);

namespace App\Enums\Workspace;

/**
 * The set of languages the app supports, and the single source of truth for it:
 * request validation, the brand analyzer's structured-output enum, homepage
 * language detection, the AI image prompt's language name, the content-language
 * picker options, and the right-to-left direction of the UI all derive from it.
 *
 * The string value is the language code stored on the workspace and passed
 * straight to the content prompts (`content_language`); the same codes also back
 * the application's UI locales, so `direction()` drives the document `dir` attribute.
 */
enum ContentLanguage: string
{
    case English = 'en';
    case Arabic = 'ar';

    public const DEFAULT = self::English;

    /**
     * The language's own name, shown in the content-language picker.
     */
    public function label(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Arabic => 'العربية',
        };
    }

    /**
     * The English name of the language, injected into AI image prompts so the
     * in-image text is rendered in the workspace's content language.
     */
    public function englishName(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Arabic => 'Arabic',
        };
    }

    /**
     * The text direction (`ltr` or `rtl`) for the document root when this is the
     * active UI locale — only Arabic is written right to left.
     */
    public function direction(): string
    {
        return $this === self::Arabic ? 'rtl' : 'ltr';
    }

    /**
     * Resolve a raw `<html lang>` value (e.g. "pt-PT", "zh-Hans") to a supported
     * language by matching its primary subtag, or null if none is supported.
     */
    public static function fromHtmlLang(string $lang): ?self
    {
        $subtag = self::primarySubtag($lang);

        if (strlen($subtag) < 2) {
            return null;
        }

        foreach (self::cases() as $language) {
            if (self::primarySubtag($language->value) === $subtag) {
                return $language;
            }
        }

        return null;
    }

    /**
     * The lowercased primary subtag of a BCP 47 language tag ("pt-BR" => "pt").
     */
    private static function primarySubtag(string $tag): string
    {
        return strtolower(explode('-', trim($tag), 2)[0]);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $language) => $language->value, self::cases());
    }

    /**
     * @return array<int, array{value: string, label: string, englishName: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $language) => [
                'value' => $language->value,
                'label' => $language->label(),
                'englishName' => $language->englishName(),
            ],
            self::cases(),
        );
    }
}
