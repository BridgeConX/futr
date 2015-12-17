<?php

namespace App\Http\Controllers;

use App\Job;
use App\Jobs\CreateJob;

class JobsController extends Controller
{

    /**
     * @var array
     */
    protected $allowedEagerLoads = [
      'attempts',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::all();

        return $this->collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->doValidation();

        $job = $this->dispatch(new CreateJob($this->request));

        $this->loadedEagerLoads[] = 'attempts';
        $job->load('attempts');

        return $this->model($job);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::findOrFail($id);

        return $this->model($job);
    }

    ///**
    // * Update the specified resource in storage.
    // *
    // * @param  \Illuminate\Http\Request $request
    // * @param  int                      $id
    // *
    // * @return \Illuminate\Http\Response
    // */
    //public function update(Request $request, $id)
    //{
    //    //
    //}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);

        $job->delete();

        return response()
          ->json([
            'status' => 'ok',
            'job_id' => $job->id,
          ]);
    }

    /**
     *
     */
    protected function doValidation()
    {
        $this->validate($this->request, [
          'method'         => 'required|in:GET,POST,PUT,PATCH,DELETE',
          'payload'        => 'required|array',
          'callback_url'   => 'url',
          'start_at'       => 'date_format:Y-m-d H:i',
          'time'           => 'required|date_format:H:i',
          'retries'        => 'numeric',
          'retry_interval' => 'numeric|required_with:retries',
        ]);
    }
}