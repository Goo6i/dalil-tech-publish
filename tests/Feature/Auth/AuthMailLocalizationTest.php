<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;

function renderVerifyMail(User $user): array
{
    $mail = (new VerifyEmail)->toMail($user);

    return [$mail->subject, (string) $mail->render()];
}

test('verification email renders in English, LTR, with the Dalil Tech footer', function () {
    app()->setLocale('en');
    $user = User::factory()->unverified()->create(['name' => 'Sara']);

    [$subject, $html] = renderVerifyMail($user);

    expect($subject)->toBe('Verify your email address')
        ->and($html)->toContain('<html lang="en" dir="ltr"')
        ->and($html)->toContain('Hi Sara,')
        ->and($html)->toContain('Verify Email Address')
        ->and($html)->toContain('https://dalil-tech.com/publish')
        ->and($html)->toContain(date('Y').' Dalil Tech')
        ->and($html)->not->toContain('trypost')
        ->and($html)->not->toContain('TryPost');
});

test('verification email renders in Arabic and RTL for an Arabic session', function () {
    app()->setLocale('ar');
    $user = User::factory()->unverified()->create(['name' => 'سارة']);

    [$subject, $html] = renderVerifyMail($user);

    expect($subject)->toBe('أكّد بريدك الإلكتروني')
        ->and($html)->toContain('<html lang="ar" dir="rtl"')
        ->and($html)->toContain('مرحباً سارة،')
        ->and($html)->toContain('تأكيد البريد الإلكتروني')
        ->and($html)->not->toContain('Best regards');

    app()->setLocale('en');
});

test('password reset email is localized and carries the reset link', function () {
    app()->setLocale('ar');
    $user = User::factory()->create();

    $mail = (new ResetPassword('token-123'))->toMail($user);
    $html = (string) $mail->render();

    expect($mail->subject)->toBe('إعادة تعيين كلمة المرور')
        ->and($html)->toContain('dir="rtl"')
        ->and($html)->toContain('token-123')
        ->and($html)->toContain('إعادة تعيين كلمة المرور')
        ->and($html)->not->toContain('TryPost');

    app()->setLocale('en');
});

test('every mail template dropped the vendor footer', function () {
    foreach (glob(resource_path('views/mail/*.blade.php')) as $template) {
        expect(file_get_contents($template))
            ->not->toContain('trypostit')
            ->not->toContain('trypost.it')
            ->not->toContain('Open-source social media scheduling tool');
    }
});
