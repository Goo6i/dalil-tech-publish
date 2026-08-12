<?php

declare(strict_types=1);

namespace App\Support;

use ArPHP\I18N\Arabic;

/**
 * Helpers for rendering Arabic onto raster images with GD, which does no
 * Arabic shaping or bidi on its own.
 */
final class ArabicText
{
    /** True when the string contains Arabic-script characters. */
    public static function contains(string $text): bool
    {
        return (bool) preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $text);
    }

    /**
     * Convert logical Arabic into presentation-form, bidi-ordered glyphs so
     * GD's imagettftext renders it joined and right-to-left. Latin and digit
     * runs keep their order (so "18 ريال" stays "18", not "81").
     */
    public static function shape(string $text): string
    {
        if ($text === '') {
            return '';
        }

        static $arabic = null;
        $arabic ??= new Arabic();

        return $arabic->utf8Glyphs($text, 10000, false);
    }

    /**
     * Remove emoji and pictographic symbols. GD text fonts carry no colour
     * emoji, so they render as tofu boxes on an image; the caption keeps them.
     */
    public static function stripEmoji(string $text): string
    {
        $stripped = preg_replace(
            '/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE00}-\x{FE0F}\x{200D}\x{20E3}\x{1F1E6}-\x{1F1FF}\x{2190}-\x{21FF}\x{2300}-\x{23FF}]/u',
            '',
            $text
        );

        return trim((string) preg_replace('/[ \t]{2,}/', ' ', (string) $stripped));
    }
}
