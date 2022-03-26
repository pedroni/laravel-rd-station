<?php

declare(strict_types=1);

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

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/contacts#methodPatchUpsertDetails
     */
    public function update(string $email, array $data): array
    {
        return (array) $this->client
            ->patch(sprintf('platform/contacts/email:%s', $email), $data)
            ->json();
    }

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/contacts#methodGetDetailsemail
     */
    public function find(string $email): array
    {
        return (array) $this->client
            ->get(sprintf('platform/contacts/email:%s', $email))
            ->json();
    }

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/contacts#delete_email
     */
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

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/contacts#create_tag_by_email
     */
    public function syncTags(string $email, array $tags): array
    {
        return (array) $this->client
            ->post(
                sprintf('platform/contacts/email:%s/tag', $email),
                [
                    'tags' => $tags,
                ]
            )
            ->json();
    }
}
