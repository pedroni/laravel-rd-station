<?php

declare(strict_types=1);

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

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/events#conversionEventPostDetails
     */
    public function conversion(array $payload): void
    {
        $this->client->post('platform/events', [
            'event_type' => 'CONVERSION',
            'event_family' => 'CDP',
            'payload' => $payload,
        ]);
    }

    /**
     * Event type will be `CONVERSION` and event family will be `CDP`
     *
     * @see https://developers.rdstation.com/pt-BR/reference/events#batchEventPostDetails
     */
    public function batchConversions(array $payloads): void
    {
        $this->batch('CONVERSION', 'CDP', $payloads);
    }

    /**
     * @see https://developers.rdstation.com/pt-BR/reference/events#batchEventPostDetails
     */
    public function batch(string $eventType, string $eventFamily, array $payloads): void
    {
        $this->client->post(
            'platform/events/batch',
            collect($payloads)
                ->map(fn ($payload) => [
                    'event_type' => $eventType,
                    'event_family' => $eventFamily,
                    'payload' => $payload,
                ])
                ->values()
                ->all()
        );
    }
}
