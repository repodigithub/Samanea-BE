<?php


/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api/auth', 'middleware' => ['cors']], function () use ($router) {
    $router->post("register","Auth\RegisterController@register");
    $router->get("register/teamleader","Auth\RegisterController@teamLeader");
    $router->get("register/supervisor","Auth\RegisterController@supervisor");
    $router->post("login","Auth\LoginController@login");
});

$router->group(['prefix' => 'api', 'middleware' => ['cors','jwt.verify']], function () use ($router){
    $router->post("logout","Auth\LogoutController@logout");
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', "UserManagementController@index");
        $router->get('/request', "UserManagementController@userRequest");
        $router->get('/{id}', "UserManagementController@show");
        $router->post('/', "UserManagementController@store");
        $router->put('/{id}', "UserManagementController@update");
        $router->delete('/{id}', "UserManagementController@destroy");
        $router->post('/approved/{id}', "UserManagementController@approved");
        $router->post('/rejected/{id}', "UserManagementController@rejected");
    });
});
