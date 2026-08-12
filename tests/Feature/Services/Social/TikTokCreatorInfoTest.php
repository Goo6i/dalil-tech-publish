<?php

declare(strict_types=1);

use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Workspace;
use App\Services\Social\TikTokCreatorInfo;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->workspace = Workspace::factory()->create(['user_id' => $this->user->id]);
    $this->account = SocialAccount::factory()->tiktok()->create([
        'workspace_id' => $this->workspace->id,
        'token_expires_at' => now()->addDays(1),
    ]);

    $this->service = new TikTokCreatorInfo;

    $this->api = config('trypost.platforms.tiktok.api');
});

test('it returns full creator payload from api response', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response([
            'data' => [
                'creator_nickname' => 'Paulo',
                'creator_username' => 'paulocastellano',
                'creator_avatar_url' => 'https://cdn.tiktok.com/avatar.jpg',
                'privacy_level_options' => ['PUBLIC_TO_EVERYONE', 'MUTUAL_FOLLOW_FRIENDS', 'SELF_ONLY'],
                'comment_disabled' => false,
                'duet_disabled' => true,
                'stitch_disabled' => true,
                'max_video_post_duration_sec' => 600,
            ],
        ], 200),
    ]);

    $info = $this->service->fetch($this->account);

    expect($info['creator_nickname'])->toBe('Paulo')
        ->and($info['creator_username'])->toBe('paulocastellano')
        ->and($info['creator_avatar_url'])->toBe('https://cdn.tiktok.com/avatar.jpg')
        ->and($info['privacy_level_options'])->toBe(['PUBLIC_TO_EVERYONE', 'MUTUAL_FOLLOW_FRIENDS', 'SELF_ONLY'])
        ->and($info['comment_disabled'])->toBeFalse()
        ->and($info['duet_disabled'])->toBeTrue()
        ->and($info['stitch_disabled'])->toBeTrue()
        ->and($info['max_video_post_duration_sec'])->toBe(600)
        ->and($info['available'])->toBeTrue()
        ->and($info['error_code'])->toBeNull();
});

test('it reports the creator as unavailable when the api fails', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response([
            'error' => ['code' => 'access_token_invalid'],
        ], 401),
    ]);

    $info = $this->service->fetch($this->account);

    // The composer must be able to tell "no options" apart from "TikTok never answered".
    expect($info['available'])->toBeFalse()
        ->and($info['error_code'])->toBe('access_token_invalid')
        ->and($info['creator_nickname'])->toBeNull()
        ->and($info['privacy_level_options'])->toBe([])
        ->and($info['comment_disabled'])->toBeFalse()
        ->and($info['duet_disabled'])->toBeFalse()
        ->and($info['stitch_disabled'])->toBeFalse()
        ->and($info['max_video_post_duration_sec'])->toBeNull();
});

test('it falls back to the http status when a failure carries no error code', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response(['error' => 'unauthorized'], 401),
    ]);

    $info = $this->service->fetch($this->account);

    expect($info['available'])->toBeFalse()
        ->and($info['error_code'])->toBe('http_401');
});

// TikTok answers "this creator cannot post right now" with HTTP 200 and no data.
// Treating that as success is what makes the composer render as fully functional
// for a creator who is actually blocked.
test('it treats a 200 response carrying an error code as unavailable', function (string $code) {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response([
            'data' => [],
            'error' => [
                'code' => $code,
                'message' => 'blocked',
                'log_id' => 'abc123',
            ],
        ], 200),
    ]);

    $info = $this->service->fetch($this->account);

    expect($info['available'])->toBeFalse()
        ->and($info['error_code'])->toBe($code)
        ->and($info['privacy_level_options'])->toBe([]);
})->with([
    'spam_risk_too_many_posts',
    'spam_risk_user_banned_from_posting',
    'reached_active_user_cap',
]);

test('it accepts a 200 response whose error code is ok', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response([
            'data' => ['privacy_level_options' => ['PUBLIC_TO_EVERYONE']],
            'error' => ['code' => 'ok', 'message' => '', 'log_id' => 'abc123'],
        ], 200),
    ]);

    $info = $this->service->fetch($this->account);

    expect($info['available'])->toBeTrue()
        ->and($info['error_code'])->toBeNull()
        ->and($info['privacy_level_options'])->toBe(['PUBLIC_TO_EVERYONE']);
});

test('it treats a 200 response with no data as unavailable', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response([], 200),
    ]);

    $info = $this->service->fetch($this->account);

    expect($info['available'])->toBeFalse()
        ->and($info['error_code'])->toBe('missing_data');
});

test('it never caches an unavailable result', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::sequence()
            ->push(['error' => ['code' => 'spam_risk_too_many_posts']], 200)
            ->push(['data' => ['privacy_level_options' => ['PUBLIC_TO_EVERYONE']]], 200),
    ]);

    expect($this->service->fetch($this->account)['available'])->toBeFalse();

    // A cached failure would keep the composer blocked for the full TTL after
    // TikTok recovers, so the second call has to hit the API again.
    $second = $this->service->fetch($this->account);

    expect($second['available'])->toBeTrue()
        ->and($second['privacy_level_options'])->toBe(['PUBLIC_TO_EVERYONE']);
});

test('it caches a successful result', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::sequence()
            ->push(['data' => ['privacy_level_options' => ['PUBLIC_TO_EVERYONE']]], 200)
            ->push(['error' => ['code' => 'spam_risk_too_many_posts']], 200),
    ]);

    expect($this->service->fetch($this->account)['privacy_level_options'])->toBe(['PUBLIC_TO_EVERYONE']);
    expect($this->service->fetch($this->account)['privacy_level_options'])->toBe(['PUBLIC_TO_EVERYONE']);

    Http::assertSentCount(1);
});

test('it refreshes the token before calling when expired', function () {
    $this->account->update(['token_expires_at' => now()->subMinute()]);

    Http::fake([
        $this->api.'/oauth/token/' => Http::response([
            'access_token' => 'new-token',
            'refresh_token' => 'new-refresh',
            'expires_in' => 3600,
        ], 200),
        $this->api.'/post/publish/creator_info/query/' => Http::response([
            'data' => [
                'privacy_level_options' => ['PUBLIC_TO_EVERYONE'],
            ],
        ], 200),
    ]);

    $this->service->fetch($this->account);

    Http::assertSent(fn ($request) => str_contains($request->url(), '/oauth/token/'));
    expect($this->account->fresh()->access_token)->toBe('new-token');
});

test('it sends an empty json object as the request body', function () {
    Http::fake([
        $this->api.'/post/publish/creator_info/query/' => Http::response(['data' => []], 200),
    ]);

    $this->service->fetch($this->account);

    Http::assertSent(function ($request) {
        return $request->body() === '{}';
    });
});
