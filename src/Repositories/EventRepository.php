<?php

namespace Pedroni\RdStation\Repositories;

use Pedroni\RdStation\RdStationOAuthClient;

class EventRepository
{
    private RdStationOAuthClient $client;

    public function __construct(
        RdStationOAuthClient $client
    ) {
        $this->client = $client;
    }

    public function conversion(array $data): void
    {
        $this->client->post('platform/events', [
            'event_type' => 'CONVERSION',
            'event_family' => 'CDP',
            'payload' => $data,
        ]);
    }
}
