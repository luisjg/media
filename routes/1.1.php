<?php

$router->post('/{emailUri}/photo', 'MediaController@storeImage');
$router->delete('/{emailUri}/photo', 'MediaController@deleteImage');

$router->group(['prefix' => '{type}/media/{emailUri}',  'middleware' => 'cors'], function () use ($router) {
    $router->get('/avatar', 'EntityController@getAvatar');
    $router->get('/audio', 'EntityController@getAudio');
    $router->get('/official', 'EntityController@getOfficial');
    $router->get('/', 'EntityController@getPersonsMedia');
    $router->get('/likeness', 'EntityController@getLikeness');
});
