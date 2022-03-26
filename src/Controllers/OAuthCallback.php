<?php

declare(strict_types=1);

namespace Pedroni\RdStation\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pedroni\RdStation\RdStationOAuthClient;
use Pedroni\RdStation\Support\RdStationConfig;

class OAuthCallback
{
    public function __invoke(Request $request, RdStationConfig $config, RdStationOAuthClient $client): JsonResponse
    {
        /** @var string|null */
        $code = $request->query('code');

        if ($code === null || $code === '') {
            return new JsonResponse(['message' => 'invalid code provided in the query string'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $response = $client->retrieveTokens('generate', $code);

        $config->setCode($code)
            ->useResponse($response)
            ->persist();

        return new JsonResponse(['message' => 'retrieved tokens successfully']);
    }
}
