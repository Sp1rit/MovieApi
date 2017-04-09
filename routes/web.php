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
    return $app->version();
});

$app->group(['prefix' => \App\Http\Controllers\BurningSeriesController::PROVIDER, 'as' => \App\Http\Controllers\BurningSeriesController::PROVIDER], function () use ($app) {
    $app->get('refresh', ['as' => 'refresh', 'uses' => 'BurningSeriesController@LoadSeriesList']);
    $app->get('search/{search}', ['as' => 'search', 'uses' => 'BurningSeriesController@Search']);
    $app->get('series/{id}', ['as' => 'series', 'uses' => 'BurningSeriesController@Series']);
    $app->get('series/{id}/{season}', ['as' => 'season', 'uses' => 'BurningSeriesController@Season']);
    $app->get('series/{id}/{season}/{episode}', ['as' => 'episode', 'uses' => 'BurningSeriesController@Episode']);
    $app->get('series/{id}/{season}/{episode}/{hoster}', ['as' => 'mirror', 'uses' => 'BurningSeriesController@Mirror']);
});

$app->group(['prefix' => \App\Http\Controllers\KinoxController::PROVIDER, 'as' => \App\Http\Controllers\KinoxController::PROVIDER], function () use ($app) {
    $app->get('search/{search}', ['as' => 'search', 'uses' => 'KinoxController@Search']);
    $app->get('movie/{id}', ['as' => 'movie', 'uses' => 'KinoxController@Movie']);
    $app->get('series/{id}', ['as' => 'series', 'uses' => 'KinoxController@Series']);
    $app->get('series/{id}/{season}', ['as' => 'season', 'uses' => 'KinoxController@Season']);
    $app->get('series/{id}/{season}/{episode}', ['as' => 'season', 'uses' => 'KinoxController@Episode']);
});

$app->group(['prefix' => \App\Http\Controllers\Movie4kController::PROVIDER, 'as' => \App\Http\Controllers\Movie4kController::PROVIDER], function () use ($app) {
    $app->get('search/{search}', ['as' => 'search', 'uses' => 'Movie4kController@Search']);
    $app->get('movie/{id}', ['as' => 'movie', 'uses' => 'Movie4kController@Movie']);
});