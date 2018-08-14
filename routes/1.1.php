<?php

$router->post('/{emailUri}/photo', 'MediaController@storeImage');

$router->group(['prefix' => 'student/media'], function () use ($router) {
    $router->get('/{emailUri}/avatar', 'StudentController@getAvatar');
    $router->get('/{emailUri}/audio', 'StudentController@getAudio');
    $router->get('/{emailUri}/official', 'StudentController@getOfficial');
    $router->get('/{emailUri}/likeness', 'StudentController@getOfficial');
    $router->get('/{emailUri}', 'StudentController@getPersonsMedia');
});

$router->group(['prefix' => 'staff/media'], function () use ($router) {
    $router->get('/{emailUri}/avatar', 'StaffController@getAvatar');
    $router->get('/{emailUri}/audio', 'StaffController@getAudio');
    $router->get('/{emailUri}/official', 'StaffController@getOfficial');
    $router->get('/{emailUri}', 'StaffController@getPersonsMedia');
});

$router->group(['prefix' => 'faculty/media'], function () use ($router) {
    $router->get('/{emailUri}/avatar', 'FacultyController@getAvatar');
    $router->get('/{emailUri}/audio', 'FacultyController@getAudio');
    $router->get('/{emailUri}/official', 'FacultyController@getOfficial');
    $router->get('/{emailUri}', 'FacultyController@getPersonsMedia');
});