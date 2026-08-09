<?php

declare(strict_types=1);

namespace App\Services\Social;

use App\Models\SocialAccount;
use App\Services\Social\Concerns\HasSocialHttpClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TikTokCreatorInfo
{
    use HasSocialHttpClient;

    private string $baseUrl;

    private string $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('trypost.platforms.tiktok.api');
    }

    /**
     * @return array{
     *     creator_nickname: ?string,
     *     creator_username: ?string,
     *     creator_avatar_url: ?string,
     *     privacy_level_options: array<int, string>,
     *     comment_disabled: bool,
     *     duet_disabled: bool,
     *     stitch_disabled: bool,
     *     max_video_post_duration_sec: ?int,
     *     available: bool,
     *     error_code: ?string,
     * }
     */
    public function fetch(SocialAccount $account): array
    {
        $key = "tiktok:creator_info:{$account->id}";

        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $payload = $this->fetchFresh($account);

        // Only a usable answer is cached. Caching an unavailable result would keep
        // the composer blocked for the full TTL after TikTok (or the creator's
        // posting eligibility) recovers.
        if ($payload['available'] === true) {
            Cache::put($key, $payload, now()->addMinutes(5));
        }

        return $payload;
    }

    /**
     * @return array{
     *     creator_nickname: ?string,
     *     creator_username: ?string,
     *     creator_avatar_url: ?string,
     *     privacy_level_options: array<int, string>,
     *     comment_disabled: bool,
     *     duet_disabled: bool,
     *     stitch_disabled: bool,
     *     max_video_post_duration_sec: ?int,
     *     available: bool,
     *     error_code: ?string,
     * }
     */
    private function fetchFresh(SocialAccount $account): array
    {
        if ($account->needsProactiveTokenRefresh()) {
            app(ConnectionVerifier::class)->refreshToken($account);
        }

        $this->accessToken = $account->access_token;

        $response = $this->getHttpClient()
            ->withBody('{}', 'application/json; charset=UTF-8')
            ->post("{$this->baseUrl}/post/publish/creator_info/query/");

        if ($response->failed()) {
            Log::warning('TikTok creator_info query failed', [
                'social_account_id' => $account->id,
                'status' => $response->status(),
                'body' => $this->redactResponseBody($response->body()),
            ]);

            return $this->emptyPayload(
                $this->normalizeErrorCode(data_get($response->json(), 'error.code')) ?? 'http_'.$response->status(),
            );
        }

        $body = $response->json();
        $errorCode = $this->normalizeErrorCode(data_get($body, 'error.code'));
        $data = data_get($body, 'data');

        // TikTok reports "this creator cannot post right now" (spam_risk_too_many_posts,
        // spam_risk_user_banned_from_posting, reached_active_user_cap, …) with HTTP 200
        // and no `data`, so the status code alone can never be trusted here. Anything
        // other than `ok` means the composer must not render as a working form.
        if ($errorCode !== null && $errorCode !== 'ok') {
            Log::warning('TikTok creator_info unavailable', [
                'social_account_id' => $account->id,
                'error_code' => $errorCode,
            ]);

            return $this->emptyPayload($errorCode);
        }

        // A 200 with neither an error code nor a data object is not a usable answer
        // either — treat it as unavailable rather than as an empty-but-working form.
        if (! is_array($data) || $data === []) {
            Log::warning('TikTok creator_info returned no data', [
                'social_account_id' => $account->id,
            ]);

            return $this->emptyPayload('missing_data');
        }

        return [
            'creator_nickname' => data_get($data, 'creator_nickname'),
            'creator_username' => data_get($data, 'creator_username'),
            'creator_avatar_url' => data_get($data, 'creator_avatar_url'),
            'privacy_level_options' => data_get($data, 'privacy_level_options', []),
            'comment_disabled' => (bool) data_get($data, 'comment_disabled', false),
            'duet_disabled' => (bool) data_get($data, 'duet_disabled', false),
            'stitch_disabled' => (bool) data_get($data, 'stitch_disabled', false),
            'max_video_post_duration_sec' => data_get($data, 'max_video_post_duration_sec'),
            'available' => true,
            'error_code' => null,
        ];
    }

    private function normalizeErrorCode(mixed $code): ?string
    {
        if (! is_string($code) || trim($code) === '') {
            return null;
        }

        return strtolower(trim($code));
    }

    /**
     * @return array{
     *     creator_nickname: null,
     *     creator_username: null,
     *     creator_avatar_url: null,
     *     privacy_level_options: array<int, string>,
     *     comment_disabled: bool,
     *     duet_disabled: bool,
     *     stitch_disabled: bool,
     *     max_video_post_duration_sec: null,
     *     available: false,
     *     error_code: ?string,
     * }
     */
    private function emptyPayload(?string $errorCode = null): array
    {
        return [
            'creator_nickname' => null,
            'creator_username' => null,
            'creator_avatar_url' => null,
            'privacy_level_options' => [],
            'comment_disabled' => false,
            'duet_disabled' => false,
            'stitch_disabled' => false,
            'max_video_post_duration_sec' => null,
            'available' => false,
            'error_code' => $errorCode,
        ];
    }

    private function getHttpClient(): PendingRequest
    {
        return $this->socialHttp()->asJson()->withToken($this->accessToken);
    }
}
