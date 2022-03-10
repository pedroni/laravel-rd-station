<?php

namespace Pedroni\RdStation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class RdStationClient
{
    private PendingRequest $http;
    private string $baseUrl;
    private string $privateToken;

    public function __construct(
        string $baseUrl,
        string $privateToken
    ) {
        $this->baseUrl = $baseUrl;
        $this->privateToken = $privateToken;
        $this->http = Http::baseUrl($this->baseUrl);
    }

    /**
     * @throws RequestException
     */
    public function patch(string $url, array $data)
    {
        return $this->http->contentType('application/json')->patch($this->withToken($url), $data)->throw();
    }

    private function withToken(string $url): string
    {
        $symbol = str_contains('?', $url) || str_contains('&', $url) ? '&' : '?';

        return sprintf('%s%sapi_key=%s', $url, $symbol, $this->privateToken);
    }
}
