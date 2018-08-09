<?php

$router->group(['prefix' => '/faculty/media'], function () use ($router) {
    $router->get('/{emailUri}/avatar-image', 'MediaController@getPersonsImage');
    $router->get('/{emailUri}/audio-recording', 'MediaController@getPersonsAudio');
    $router->get('/{emailUri}/photo-id-image', 'MediaController@getPersonsOfficialImage');
    $router->get('/{emailUri}', 'MediaController@getPersonsMedia');
});