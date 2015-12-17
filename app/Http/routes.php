<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', [
  'as'          => 'home',
  'description' => 'Get API Details',
  function () use ($app) {
      return [
        'name'        => 'Futr',
        'description' => 'A REST API for scheduling Guzzle Requests in the future.',
        'version'     => '0.0.1',
      ];
  },
]);

$app->get('routes', [
  'as'          => 'routes',
  'description' => 'List API Routes',
  function () use ($app) {
      return $app->getRoutes();
  },
]);

$app->group([
  'prefix'     => 'jobs',
  'namespace'  => 'App\Http\Controllers',
  'middleware' => 'json',
], function () use ($app) {

    $app->get('/', [
      'as'          => 'jobs.index',
      'uses'        => 'JobsController@index',
      'description' => 'List all jobs',
    ]);

    $app->get('{jobs}', [
      'as'          => 'jobs.show',
      'uses'        => 'JobsController@show',
      'description' => 'Show a Job',
    ]);

    $app->post('/', [
      'as'          => 'jobs.store',
      'uses'        => 'JobsController@store',
      'description' => 'Create a Job',
    ]);

    $app->delete('{jobs}', [
      'as'          => 'jobs.destroy',
      'uses'        => 'JobsController@destroy',
      'description' => 'Cancells Job and all future Attempts',
    ]);
});

$app->group([
  'prefix'     => 'jobs/{jobs}/attempts',
  'namespace'  => 'App\Http\Controllers',
  'middleware' => 'json',
], function () use ($app) {

    $app->get('/', [
      'as'          => 'jobs.attempts.index',
      'uses'        => 'AttemptsController@index',
      'description' => 'Show a Job\'s Attempts',
    ]);

    $app->get('{attempts}', [
      'as'          => 'jobs.attempts.show',
      'uses'        => 'AttemptsController@show',
      'description' => 'Show an Attempt',
    ]);

    $app->post('{attempts}/try', [
      'as'          => 'jobs.attempts.try',
      'uses'        => 'AttemptsController@tryAttempt',
      'description' => 'Execute attempt, only if it has not yet been attempted',
    ]);

    $app->post('{attempts}/retry', [
      'as'          => 'jobs.attempts.retry',
      'uses'        => 'AttemptsController@retryAttempt',
      'description' => 'Execute attempt, whether or not it has already been attempted',
    ]);
});