<?php

namespace Pedroni\RdStation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Pedroni\RdStation\Support\Definitions\RetrieveTokensResponse;
use Pedroni\RdStation\Support\RdStationConfig;
use RuntimeException;

class RdStationOAuthClient
{
    private PendingRequest $http;
    private RdStationConfig $config;

    public function __construct(
        RdStationConfig $config
    ) {
        $this->config = $config;

        $this->http = Http::baseUrl($this->config->apiBaseUrl())
            ->contentType('application/json');
    }

    public function withToken(): PendingRequest
    {
        if ($this->config->isExpired()) {
            $this->refreshAccessToken();
        }

        return $this->http->withHeaders([
            'Authorization' => sprintf('Bearer %s', $this->config->accessToken()),
        ]);
    }

    public function post(string $url, array $data): Response
    {
        return $this->withToken()->post($url, $data)->throw();
    }

    public function get(string $url, array $data): Response
    {
        return $this->withToken()->post($url, $data)->throw();
    }

    public function patch(string $url, array $data): Response
    {
        return $this->withToken()->patch($url, $data)->throw();
    }

    public function delete(string $url, array $data): Response
    {
        return $this->withToken()->delete($url, $data)->throw();
    }

    public function retrieveTokens(string $strategy, string $value): RetrieveTokensResponse
    {
        if (! in_array($strategy, ['refresh', 'generate'])) {
            throw new InvalidArgumentException(sprintf('RdStationOAuthClient::retrieveTokens $strategy argument was invalid found `%s` but the only strategies found are `refresh` or `generate`.', $strategy));
        }

        $key = $strategy === 'refresh' ? 'refresh_token' : 'code';

        /** @var array{access_token: string, refresh_token: string, expires_in: int} */
        $data = $this->http->post('auth/token', [
            'client_id' => $this->config->clientId(),
            'client_secret' => $this->config->clientSecret(),
            $key => $value,
        ])
            ->throw()
            ->json();

        return new RetrieveTokensResponse($data['access_token'], $data['refresh_token'], (int) $data['expires_in']);
    }

    public function refreshAccessToken(): void
    {
        if ($this->config->refreshToken() === null) {
            throw new RuntimeException('Cannot refresh access token without first installing the integration');
        }

        $response = $this->retrieveTokens(
            'refresh',
            $this->config->refreshToken()
        );

        $this->config
            ->useResponse($response)
            ->persist();
    }
}
