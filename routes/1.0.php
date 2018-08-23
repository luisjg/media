<?php

$router->group(['middleware' => 'cors'], function () use ($router) {
    $router->get('/faculty/media/{emailUri}', 'MediaController@getPersonsMedia');
    $router->get('/{emailUri}/avatar', 'MediaController@getPersonsAvatarImage');
    $router->get('/{emailUri}/audio', 'MediaController@getPersonsAudio');
    $router->get('/{emailUri}/official', 'MediaController@getPersonsOfficialImage');
});