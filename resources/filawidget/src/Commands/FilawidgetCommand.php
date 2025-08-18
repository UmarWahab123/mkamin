<?php

namespace Filawidget\Commands;

use Illuminate\Console\Command;

class FilawidgetCommand extends Command
{
    public $signature = 'filawidget';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
