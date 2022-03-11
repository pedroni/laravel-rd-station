<?php

namespace Pedroni\RdStation\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pedroni\RdStation\RdStationOAuthClient;
use Pedroni\RdStation\Support\RdStationConfig;

class OAuthCallback
{
    public function __invoke(Request $request, RdStationConfig $config, RdStationOAuthClient $client)
    {
        /** @var string|null */
        $code = $request->query('code');

        if ($code === null || $code === '') {
            return new JsonResponse(['message' => 'invalid code provided in the query string'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $response = $client->retrieveTokens($code);

        $config->setCode($code)
            ->setAccessToken($response->accessToken())
            ->setRefreshToken($response->refreshToken())
            ->setExpiresAt(
                now()
                    ->addSeconds($response->expiresIn())
                    ->toImmutable()
            )
            ->persist();

        return new JsonResponse(['message' => 'retrieved tokens successfully']);
    }
}
