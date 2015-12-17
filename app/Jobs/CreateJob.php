<?php

namespace App\Jobs;

use App\Attempt;
use App\Job;
use App\Jobs\Job as DispatchableJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreateJob extends DispatchableJob
{

    /**
     * @var Request
     */
    private $request;

    /**
     * CreateJob constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Job
     */
    public function handle()
    {
        $job = Job::create([
          'method'         => $this->request->get('method'),
          'payload'        => $this->request->get('payload', []),
          'callback_url'   => $this->request->get('callback_url', null),
          'start_at'       => $this->getStartAt(),
          'time'           => $this->request->get('time'),
          'retries'        => $this->request->get('retries', 1),
          'retry_interval' => $this->request->get('retry_interval'),
        ]);

        $this->createAttempts($job);

        return $job;
    }

    /**
     * @return Carbon
     */
    protected function getStartAt()
    {
        if (! $this->request->has('start_at')) {
            return Carbon::now();
        }

        return Carbon::createFromFormat('Y-m-d H:i', $this->request->get('start_at'));
    }

    /**
     * @param Job $job
     */
    protected function createAttempts(Job $job)
    {

        $nextAttempt = $this->getFirstAttempt();

        foreach (range(1, $this->request->get('retries', 1)) as $retry) {
            $attempt = new Attempt([
              'try_at' => $nextAttempt,
            ]);

            $job->attempts()->save($attempt);

            $nextAttempt->addMinutes($this->request->get('retry_interval'));
        }
    }

    /**
     * @return Carbon
     */
    protected function getFirstAttempt()
    {
        $startAt = $this->getStartAt();
        $withTime = $this->getStartAt()->setTimeFromTimeString($this->request->get('time'));

        return $startAt->gt($withTime) ? $withTime->addDay() : $withTime;
    }
}