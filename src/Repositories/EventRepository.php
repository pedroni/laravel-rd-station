<?php

namespace Pedroni\RdStation\Repositories;

use Pedroni\RdStation\RdStationClient;

class EventRepository
{
    private RdStationClient $client;

    public function __construct(
        RdStationClient $client
    ) {
        $this->client = $client;
    }

    public function conversion(array $data): void
    {
        $this->client->post('platform/events', [
            'event_type' => 'CONVERSION',
            'event_family' => 'CDP',
            'payload' => $data
        ]);
    }
}
