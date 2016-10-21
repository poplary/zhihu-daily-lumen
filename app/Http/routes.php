<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/', 'Api\V1\ApiController@index');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
        $api->post('register', [
            'as'   => 'register',
            'uses' => 'AuthController@register',
        ]);

        $api->post('auth', [
            'as'   => 'auth',
            'uses' => 'AuthController@authenticate',
        ]);

        $api->group(['prefix' => 'user', 'middleware' => ['auth']], function ($api) {
            $api->get('profile/me', [
                'as'   => 'user.profile',
                'uses' => 'UserController@profile',
            ]);

            $api->get('profile/{id}', [
                'as'   => 'user.profile',
                'uses' => 'UserController@profile',
            ]);
        });

        $api->group([], function ($api) {
            $api->get('zhihu/latest', [
                'as'   => 'zhihu.latest',
                'uses' => 'ZhihuController@latest',
            ]);

            $api->get('zhihu/history/{date}', [
                'as'   => 'zhihu.day',
                'uses' => 'ZhihuController@history',
            ]);

            $api->get('profile/{id}', [
                'as'   => 'user.profile',
                'uses' => 'UserController@profile',
            ]);
        });
    });
});
