<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestJobCommand extends Command
{
    protected $signature = 'test:job';

    protected $description = 'Command description';

    public function handle()
    {
        dispatch(new TestJob());
    }
}
