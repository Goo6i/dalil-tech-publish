<?php

declare(strict_types=1);

namespace App\Support;

final class Registration
{
    /**
     * Public sign ups are open. Explicit PUBLIC_REGISTRATION wins; when it is
     * unset, self-hosted installs stay invite-only and cloud installs are open.
     */
    public static function isPublic(): bool
    {
        $explicit = config('trypost.public_registration');

        if ($explicit !== null && $explicit !== '') {
            return filter_var($explicit, FILTER_VALIDATE_BOOLEAN);
        }

        return ! config('trypost.self_hosted');
    }
}
