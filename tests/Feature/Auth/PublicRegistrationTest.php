<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\Registration;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(fn () => RateLimiter::clear('register'));

test('registration follows self_hosted when PUBLIC_REGISTRATION is unset', function () {
    config()->set('trypost.public_registration', null);

    config()->set('trypost.self_hosted', true);
    expect(Registration::isPublic())->toBeFalse();

    config()->set('trypost.self_hosted', false);
    expect(Registration::isPublic())->toBeTrue();
});

test('PUBLIC_REGISTRATION=true opens sign ups on a self-hosted install', function () {
    config()->set('trypost.self_hosted', true);
    config()->set('trypost.public_registration', 'true');

    $this->get(route('register'))->assertOk();

    $response = $this->post(route('register.store'), [
        'name' => 'Public User',
        'email' => 'public@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    expect(User::where('email', 'public@example.com')->exists())->toBeTrue();
});

test('PUBLIC_REGISTRATION=false closes sign ups even when not self-hosted', function () {
    config()->set('trypost.self_hosted', false);
    config()->set('trypost.public_registration', 'false');

    $this->get(route('register'))->assertNotFound();

    $this->post(route('register.store'), [
        'name' => 'Nobody',
        'email' => 'nobody@example.com',
        'password' => 'Password123!',
    ])->assertNotFound();

    expect(User::where('email', 'nobody@example.com')->exists())->toBeFalse();
});

test('login page exposes registrationEnabled from the public-registration gate', function () {
    config()->set('trypost.self_hosted', true);
    config()->set('trypost.public_registration', 'true');

    $page = $this->get(route('login'))->assertOk()->original->getData()['page'];
    expect($page['props']['registrationEnabled'])->toBeTrue();

    config()->set('trypost.public_registration', 'false');

    $page = $this->get(route('login'))->assertOk()->original->getData()['page'];
    expect($page['props']['registrationEnabled'])->toBeFalse();
});

test('a public sign up is unverified and receives the verification email', function () {
    Notification::fake();
    config()->set('trypost.self_hosted', true);
    config()->set('trypost.public_registration', 'true');

    $this->post(route('register.store'), [
        'name' => 'Public User',
        'email' => 'public@example.com',
        'password' => 'Password123!',
    ]);

    $user = User::where('email', 'public@example.com')->firstOrFail();

    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmail::class);
});

test('an unverified user is held on the verification notice until they verify', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('app.calendar'))
        ->assertRedirect(route('verification.notice', absolute: false));

    $this->actingAs($user)->get(route('verification.notice'))->assertOk();
});

test('register POST is throttled per IP', function () {
    config()->set('trypost.self_hosted', false);

    foreach (range(1, 5) as $i) {
        $this->post(route('register.store'), [
            'name' => "User {$i}",
            'email' => "user{$i}@example.com",
            'password' => 'Password123!',
        ])->assertSessionHasNoErrors();

        auth()->logout();
    }

    $this->post(route('register.store'), [
        'name' => 'User 6',
        'email' => 'user6@example.com',
        'password' => 'Password123!',
    ])->assertStatus(429);

    expect(User::where('email', 'user6@example.com')->exists())->toBeFalse();
});
