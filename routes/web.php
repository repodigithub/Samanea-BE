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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->group(['prefix' => 'api/auth'], function () use ($router) {
    $router->post("register","Auth\RegisterController@register");
    $router->post("login","Auth\LoginController@login");
});

$router->group(['prefix' => 'api', 'middleware' => ['jwt.verify']], function () use ($router){
    $router->post("logout","Auth\LogoutController@logout");
    $router->group(['prefix' => 'role'], function () use ($router) {
        $router->get('/', "RoleController@index");
        $router->post('/', "RoleController@store");
        $router->get('/{id}', "RoleController@show");
        $router->put('/{id}', "RoleController@update");
        $router->delete('/{id}', 'RoleController@destroy');
    });
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', "UserManagementController@index");
        $router->post('/approved/{id}', "UserManagementController@approved");
        $router->post('/rejected/{id}', "UserManagementController@rejected");
    });
});
