<?php

declare(strict_types=1);

namespace Pedroni\RdStation\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Pedroni\RdStation\Support\RdStationConfig;

class OAuthInstall
{
    public function __invoke(RdStationConfig $config, ResponseFactory $responseFactory): RedirectResponse
    {
        return $responseFactory->redirectTo(
            sprintf(
                '%s/auth/dialog?client_id=%s&redirect_uri=%s',
                $config->apiBaseUrl(),
                $config->clientId(),
                urlencode($config->redirectUrl())
            )
        );
    }
}
