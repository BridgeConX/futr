<?php

namespace App\Http\Controllers;

use App\Job;
use App\Jobs\ExecuteAttempt;

class AttemptsController extends Controller
{

    /**
     * @var array
     */
    protected $allowedEagerLoads = [
      'job',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param int $jobId
     *
     * @return \Illuminate\Http\Response
     */
    public function index($jobId)
    {
        $job = Job::findOrFail($jobId);

        return $this->collection($job->attempts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $jobId
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($jobId, $id)
    {
        $job = Job::findOrFail($jobId);

        return $this->model($job->attempts()->findOrFail($id));
    }

    /**
     * @param $jobId
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tryAttempt($jobId, $id)
    {
        /** @var Job $job */
        $job = Job::findOrFail($jobId);
        $attempt = $job->attempts()->findOrFail($id);

        if (! $attempt->tried) {
            $this->dispatch(new ExecuteAttempt($attempt));
        }

        return $this->model($attempt);
    }

    /**
     * @param $jobId
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retryAttempt($jobId, $id)
    {
        /** @var Job $job */
        $job = Job::findOrFail($jobId);
        $attempt = $job->attempts()->findOrFail($id);

        $this->dispatch(new ExecuteAttempt($attempt));

        return $this->model($attempt);
    }

}