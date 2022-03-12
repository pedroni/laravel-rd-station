<?php

namespace Pedroni\RdStation\Repositories;

use Illuminate\Http\Client\RequestException;
use Pedroni\RdStation\RdStationOAuthClient;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ContactRepository
{
    private RdStationOAuthClient $client;

    public function __construct(
        RdStationOAuthClient $client
    ) {
        $this->client = $client;
    }

    public function update(string $email, array $data): array
    {
        return $this->client
            ->patch(sprintf('platform/contacts/email:%s', $email), $data)
            ->json();
    }

    public function find(string $email): array
    {
        return $this->client
            ->get(sprintf('platform/contacts/email:%s', $email))
            ->json();
    }

    public function delete(string $email): void
    {
        try {
            $this->client->delete(sprintf('platform/contacts/email:%s', $email));
        } catch (RequestException $exception) {
            if ($exception->response->status() !== HttpFoundationResponse::HTTP_NOT_FOUND) {
                throw $exception;
            }
        }
    }

    public function syncTags(string $email, array $tags): array
    {
        return $this->client
            ->post(
                sprintf('platform/contacts/email:%s/tag', $email),
                [
                    'tags' => $tags,
                ]
            )
            ->json();
    }
}
