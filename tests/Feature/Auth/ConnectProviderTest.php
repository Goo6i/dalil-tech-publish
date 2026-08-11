<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

test('authenticated user can hit the connect-provider route for google', function () {
    $user = User::factory()->create();

    $driver = Mockery::mock(AbstractProvider::class);
    $driver->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));
    Socialite::shouldReceive('driver')->with('google-auth')->andReturn($driver);

    $this->actingAs($user)
        ->get(route('app.authentication.connect-provider', 'google'))
        ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
});

test('connect-provider route rejects unknown provider', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('app.authentication.connect-provider', 'twitter'))
        ->assertNotFound();
});

test('connect-provider route requires authentication', function () {
    $this->get(route('app.authentication.connect-provider', 'google'))
        ->assertRedirect(route('login'));
});

test('authenticated callback connects google to the current user', function () {
    $user = User::factory()->create([
        'email' => 'me@example.com',
        'google_id' => null,
    ]);

    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = 'g-me';
    $socialiteUser->name = 'Me';
    $socialiteUser->email = 'me@example.com';

    $driver = Mockery::mock(AbstractProvider::class);
    $driver->shouldReceive('user')->andReturn($socialiteUser);
    Socialite::shouldReceive('driver')->with('google-auth')->andReturn($driver);

    $this->actingAs($user)
        ->get(route('auth.google.callback'))
        ->assertRedirect(route('app.authentication.edit'))
        ->assertSessionHas('flash.success');

    expect($user->fresh()->google_id)->toBe('g-me');
});

test('authenticated callback links google by current user, not by email', function () {
    $user = User::factory()->create([
        'email' => 'work@example.com',
        'google_id' => null,
    ]);

    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = 'g-personal';
    $socialiteUser->name = 'Me';
    $socialiteUser->email = 'personal@example.com';

    $driver = Mockery::mock(AbstractProvider::class);
    $driver->shouldReceive('user')->andReturn($socialiteUser);
    Socialite::shouldReceive('driver')->with('google-auth')->andReturn($driver);

    $this->actingAs($user)
        ->get(route('auth.google.callback'))
        ->assertRedirect(route('app.authentication.edit'))
        ->assertSessionHas('flash.success');

    expect($user->fresh()->google_id)->toBe('g-personal');
    expect(User::where('email', 'personal@example.com')->exists())->toBeFalse();
    $this->assertAuthenticatedAs($user);
});

test('authenticated callback rejects when google account is already linked to another user', function () {
    User::factory()->create(['google_id' => 'g-taken']);

    $me = User::factory()->create(['email' => 'me@example.com', 'google_id' => null]);

    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = 'g-taken';
    $socialiteUser->name = 'Me';
    $socialiteUser->email = 'me@example.com';

    $driver = Mockery::mock(AbstractProvider::class);
    $driver->shouldReceive('user')->andReturn($socialiteUser);
    Socialite::shouldReceive('driver')->with('google-auth')->andReturn($driver);

    $this->actingAs($me)
        ->get(route('auth.google.callback'))
        ->assertRedirect(route('app.authentication.edit'))
        ->assertSessionHas('flash.error');

    expect($me->fresh()->google_id)->toBeNull();
    $this->assertAuthenticatedAs($me);
});
