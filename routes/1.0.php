<?php

$router->get('/{emailUrie}/avatar', 'MediaController@getPersonsImage');
$router->get('/{emailUri}/audio', 'MediaController@getPersonsAudio');
$router->get('/{emailUri}/official', 'MediaController@getPersonsOfficialImage');
$router->get('/faculty/media/{emailUri}', 'MediaController@getPersonsMedia');

$router->post('/{emailUri}/post', 'MediaController@storeImage');