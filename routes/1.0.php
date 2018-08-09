<?php

$router->group(['prefix' => '/faculty/media'], function () use ($router) {
    $router->get('/{emailUri}/avatar', 'MediaController@getPersonsImage');
    $router->get('/{emailUri}/audio', 'MediaController@getPersonsAudio');
    $router->get('/{emailUri}/official', 'MediaController@getPersonsOfficialImage');
    $router->get('/{emailUri}', 'MediaController@getPersonsMedia');
});