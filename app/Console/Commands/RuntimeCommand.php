<?php

namespace App\Console\Commands;

use App\Jobs\RuntimeJob;
use Illuminate\Console\Command;

class RuntimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RuntimeJob::dispatch(1);
    }
}
