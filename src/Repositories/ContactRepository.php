<?php

namespace Pedroni\RdStation\Repositories;

use Illuminate\Http\Client\RequestException;
use Pedroni\RdStation\Exceptions\UnableToUpdateOrCreateEntity;
use Pedroni\RdStation\RdStationClient;

class ContactRepository
{
    private RdStationClient $client;

    public function __construct(
        RdStationClient $client
    ) {
        $this->client = $client;
    }

    /**
     * @throws UnableToUpdateOrCreateEntity
     */
    public function updateOrCreate(string $email, array $data): void
    {
        try {
            $this->client->patch(sprintf('platform/contacts/email:%s', $email), $data);
        } catch (RequestException $exception) {
            throw new UnableToUpdateOrCreateEntity(sprintf('Unable to update or create entity identified by %s', $email), 0, $exception);
        }
    }
}
