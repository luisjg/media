<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$router->group(['prefix' => '1.0'], function () use($router) {
    $router->get('/{emailUri}/avatar', 'MediaController@getPersonsAvatarImage');
    $router->get('/{emailUri}/audio', 'MediaController@getPersonsAudio');
    $router->get('/{emailUri}/official', 'MediaController@getPersonsOfficialImage');
    $router->get('/faculty/media/{emailUri}', 'MediaController@getPersonsMedia');
});