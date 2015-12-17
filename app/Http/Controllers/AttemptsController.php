<?php

namespace App\Http\Controllers;

use App\Job;

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

        return $this->model($job->attempts()->find($id));
    }

}