<?php

namespace App\Jobs;

use App\Attempt;
use App\Job;
use App\Jobs\Job as DispatchableJob;
use GuzzleHttp\Client;

class ExecuteAttempt extends DispatchableJob
{

    /**
     * @var Attempt
     */
    private $attempt;

    /**
     * ExecuteAttempt constructor.
     *
     * @param Attempt $attempt
     */
    public function __construct(Attempt $attempt)
    {
        $this->attempt = $attempt;
    }

    /**
     *
     */
    public function handle()
    {
        /** @var Job $job */
        $job = $this->attempt->job;

        $this->attempt($job);

        $this->sendCallback($job);
    }

    /**
     * @param Job $job
     */
    protected function attempt(Job $job)
    {
        $response = $this->getClient()
          ->request($job->method, null, $job->payload);

        $this->attempt->markAttempted($response->getStatusCode(), $response);
    }

    /**
     * @param Job $job
     */
    protected function sendCallback(Job $job)
    {
        $this->getClient()
          ->request('POST', $job->callback_url, [
            'json' => $this->attempt,
          ]);
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return new Client([
          'exceptions' => false,
        ]);
    }
}