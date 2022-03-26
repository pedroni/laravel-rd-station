<?php

declare(strict_types=1);

namespace Pedroni\RdStation;

use Pedroni\RdStation\Repositories\ContactRepository;
use Pedroni\RdStation\Repositories\EventRepository;

class RdStation
{
    private ContactRepository $contactRepository;
    private EventRepository $eventRepository;

    public function __construct(
        ContactRepository $contactRepository,
        EventRepository $eventRepository
    ) {
        $this->contactRepository = $contactRepository;
        $this->eventRepository = $eventRepository;
    }

    public function contacts(): ContactRepository
    {
        return $this->contactRepository;
    }

    public function events(): EventRepository
    {
        return $this->eventRepository;
    }
}
