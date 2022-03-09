<?php

namespace Pedroni\RdStation\Commands;

use Illuminate\Console\Command;

class RdStationCommand extends Command
{
    public $signature = 'rd-station';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
