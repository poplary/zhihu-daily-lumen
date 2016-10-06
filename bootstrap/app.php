<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

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

class_alias('Tymon\JWTAuth\Facades\JWTAuth', 'JWTAuth');

$app->withEloquent();

//config
// 加载jwt配置文件
$app->configure('jwt');

// 加载auth配置文件
$app->configure('auth');

// 加载cors配置文件
$app->configure('cors');

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
    // App\Http\Middleware\ExampleMiddleware::class
    Barryvdh\Cors\HandleCors::class,
    Barryvdh\Cors\HandlePreflightSimple::class
]);

$app->routeMiddleware([
    // auth 中间件
    'auth' => App\Http\Middleware\Authenticate::class,

    // jwt 中间件
    'jwt.auth' => Tymon\JWTAuth\Middleware\GetUserFromToken::class,
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

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

// 注册redis
$app->register(Illuminate\Redis\RedisServiceProvider::class);

// 注册dingo
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);

// 注册jwt
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

// 注册cors
$app->register(Barryvdh\Cors\LumenServiceProvider::class);

// dingo自定义相应格式
Dingo\Api\Http\Response::addFormatter('json', new Dingo\Api\Http\Response\Format\Jsonp);

// 认证提供者jwt
app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
    return new Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
});

// 插入认证
$app->singleton(Illuminate\Auth\AuthManager::class, function ($app) {
    return $app->make('auth');
});

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

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
