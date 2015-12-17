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

$app->get('/', function () {
    return [
      'name'        => 'Futr',
      'description' => 'A REST API for scheduling Guzzle Requests in the future.',
      'version'     => '0.0.1',
    ];
});

$app->group([
  'prefix'       => 'jobs',
  'route_prefix' => 'jobs.',
  'namespace'    => 'App\Http\Controllers',
], function () use ($app) {

    $app->get('/', [
      'as'   => 'index',
      'uses' => 'JobsController@index',
    ]);

    $app->get('{jobs}', [
      'as'   => 'show',
      'uses' => 'JobsController@show',
    ]);

    $app->post('/', [
      'as'   => 'store',
      'uses' => 'JobsController@store',
    ]);

    $app->delete('{jobs}', [
      'as'   => 'destroy',
      'uses' => 'JobsController@destroy',
    ]);

});