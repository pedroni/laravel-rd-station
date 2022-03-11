<?php

namespace Pedroni\RdStation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Support\Definitions\RetrieveTokensResponse;
use Pedroni\RdStation\Support\RdStationConfig;

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

    public function retrieveTokens(string $code): RetrieveTokensResponse
    {
        /** @var array{access_token: int, refresh_token: int, expires_in: int} */
        $data = $this->http->post('auth/token', [
            'client_id' => $this->config->clientId(),
            'client_secret' => $this->config->clientSecret(),
            'code' => $code,
        ])
            ->throw()
            ->json();

        return new RetrieveTokensResponse($data['access_token'], $data['refresh_token'], (int) $data['expires_in']);
    }
}
