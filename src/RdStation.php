<?php

namespace Pedroni\RdStation;

use Pedroni\RdStation\Repositories\ContactRepository;

class RdStation
{
    private ContactRepository $contactRepository;

    public function __construct(
        ContactRepository $contactRepository
    ) {
        $this->contactRepository = $contactRepository;
    }

    public function contacts(): ContactRepository
    {
        return $this->contactRepository;
    }
}
