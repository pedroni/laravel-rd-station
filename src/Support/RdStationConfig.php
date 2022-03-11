<?php

namespace Pedroni\RdStation\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class RdStationConfig
{
    const TABLE = 'rd_station_config';

    private string $apiBaseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $redirectPath;

    private ?string $accessToken;
    private ?string $refreshToken;
    private ?string $code;
    private ?CarbonImmutable $expiresAt;

    private function __construct(
        string $apiBaseUrl,
        string $clientId,
        string $clientSecret,
        string $redirectPath,

        ?string $accessToken,
        ?string $refreshToken,
        ?string $code,
        ?CarbonImmutable $expiresAt
    ) {
        $this->apiBaseUrl = $apiBaseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectPath = $redirectPath;

        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->code = $code;
        $this->expiresAt = $expiresAt;
    }

    public static function make(string $apiBaseUrl, string $clientId, string $clientSecret, string $redirectPath): self
    {
        $config = DB::table(self::TABLE)->first();

        if (!$config) {
            DB::table(self::TABLE)->insert([]);
            $config = [];
        }

        return new self(
            $apiBaseUrl,
            $clientId,
            $clientSecret,
            $redirectPath,

            $config['access_token'] ?? null,
            $config['refresh_token'] ?? null,
            $config['code'] ?? null,
            isset($config['expires_at']) ? CarbonImmutable::createFromFormat('Y-m-d H:i:s', $config['expires_at']) : null,
        );
    }

    public function clientId(): string
    {
        return $this->clientId;
    }

    public function clientSecret(): string
    {
        return $this->clientSecret;
    }

    public function redirectUrl(): string
    {
        return url($this->redirectPath);
    }

    public function apiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
    public function code(): string
    {
        return $this->code;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setExpiresAt(CarbonImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function expiresAt(): CarbonImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return true;
        }

        if ($this->expiresAt->isPast()) {
            return true;
        }

        return false;
    }


    public function persist(): self
    {
        DB::table(self::TABLE)->update([
            'access_token' => $this->accessToken(),
            'refresh_token' => $this->refreshToken(),
            'code' => $this->code(),
            'expires_at' => $this->expiresAt()->format('Y-m-d H:i:s')
        ]);

        return $this;
    }
}
