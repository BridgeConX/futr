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

$app->get('/', function () use ($app) {
    return [
      'name'        => 'Futr',
      'description' => 'A REST API for scheduling Guzzle Requests in the future.',
      'version'     => '0.0.1',
    ];
});

$app->get('routes', function () use ($app) {
   return $app->getRoutes();
});

$app->group([
  'prefix'       => 'jobs',
  'namespace'    => 'App\Http\Controllers',
], function () use ($app) {

    $app->get('/', [
      'as'   => 'jobs.index',
      'uses' => 'JobsController@index',
    ]);

    $app->get('{jobs}', [
      'as'   => 'jobs.show',
      'uses' => 'JobsController@show',
    ]);

    $app->post('/', [
      'as'   => 'jobs.store',
      'uses' => 'JobsController@store',
    ]);

    $app->delete('{jobs}', [
      'as'   => 'jobs.destroy',
      'uses' => 'JobsController@destroy',
    ]);
});

$app->group([
  'prefix'       => 'jobs/{jobs}/attempts',
  'namespace'    => 'App\Http\Controllers',
], function () use ($app) {

    $app->get('/', [
      'as'   => 'jobs.attempts.index',
      'uses' => 'AttemptsController@index',
    ]);

    $app->get('{attempts}', [
      'as'   => 'jobs.attempts.show',
      'uses' => 'AttemptsController@show',
    ]);
});