<?php

namespace App\Console\Commands;

use App\Attempt;
use App\Jobs\ExecuteAttempt;
use Illuminate\Console\Command;
use Laravel\Lumen\Routing\DispatchesJobs;

class Marshal extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'futr:marshal {--p|pretend : Marhsal API Calls in Pretend Mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marshal scheduled API Calls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $attempts = Attempt::pending()->get();

        $this->info('==> Found [' . $attempts->count() . '] attempts.');

        foreach ($attempts as $attempt) {
            $this->info('==> [Start] Dispatching Attempt [' . $attempt->id . '].');

            $this->dispatch(new ExecuteAttempt($attempt));

            $this->info('==> [End] Dispatched Attempt [' . $attempt->id . '].');
        }
    }
}