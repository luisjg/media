<?php

$router->post('/{emailUri}/photo', 'MediaController@storeImage');
$router->post('/{emailUri}/photo/delete', 'MediaController@deleteImage');

$router->group(['prefix' => '{type}/media/{emailUri}'], function () use ($router) {
    $router->get('/', 'EntityController@getPersonsMedia');
    $router->get('/audio', 'EntityController@getAudio');
    $router->get('/avatar', 'EntityController@getAvatar');
    $router->get('/likeness', 'EntityController@getLikeness');
    $router->get('/official', 'EntityController@getOfficial');
});
