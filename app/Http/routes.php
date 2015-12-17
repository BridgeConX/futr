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

