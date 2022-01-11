<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Filesystem\Factory::class,
    function ($app) {
        return new Illuminate\Filesystem\FilesystemManager($app);
    }
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    CSUNMetaLab\LumenForceHttps\Http\Middleware\ForceHttps::class,
    Fruitcake\Cors\HandleCors::class,
]);

 $app->routeMiddleware([
     'auth' => App\Http\Middleware\Authenticate::class,
 ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->configure('proxypass');
$app->register(CSUNMetaLab\LumenProxyPass\Providers\ProxyPassServiceProvider::class);

$app->configure('forcehttps');
$app->register(CSUNMetaLab\LumenForceHttps\Providers\ForceHttpsServiceProvider::class);

$app->configure('filesystems');
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);

$app->configure('cors');
$app->register(Fruitcake\Cors\CorsServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix'    => 'api',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix'    => '1.0',
], function ($router) {
    require __DIR__.'/../routes/1.0.php';
});

$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix'    => '1.1',
], function ($router) {
    require __DIR__.'/../routes/1.1.php';
});

return $app;
