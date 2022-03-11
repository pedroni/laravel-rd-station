<?php

namespace Pedroni\RdStation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class RdStationClient
{
    private PendingRequest $http;
    private string $baseUrl;
    private string $apiKey;

    public function __construct(
        string $baseUrl,
        string $apiKey
    ) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->http = Http::baseUrl($this->baseUrl)
            ->contentType('application/json');
    }

    /**
     * @throws RequestException
     */
    public function patch(string $url, array $data)
    {
        return $this->http
            ->patch($this->withApiKey($url), $data)
            ->throw();
    }

    /**
     * @throws RequestException
     */
    public function post(string $url, array $data)
    {
        return $this->http
            ->post($this->withApiKey($url), $data)
            ->throw();
    }

    private function withApiKey(string $url): string
    {
        $symbol = str_contains('?', $url) || str_contains('&', $url) ? '&' : '?';

        return sprintf('%s%sapi_key=%s', $url, $symbol, $this->apiKey);
    }
}
