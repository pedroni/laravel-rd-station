<?php

namespace Pedroni\RdStation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

class RdStationClient
{
    private PendingRequest $http;

    public function __construct(
        PendingRequest $http
    ) {
        $this->http = $http;
    }

    /**
     * @throws RequestException
     */
    public function patch(string $url, array $data)
    {
        return $this->http->patch($url, $data)->throw();
    }
}
