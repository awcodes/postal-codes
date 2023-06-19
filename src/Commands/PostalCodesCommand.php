<?php

namespace Awcodes\PostalCodes\Commands;

use Illuminate\Console\Command;

class PostalCodesCommand extends Command
{
    public $signature = 'postal-codes';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
