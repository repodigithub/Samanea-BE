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

$router->group(['prefix' => 'api/auth'], function () use ($router) {
    $router->post("register","Auth\RegisterController@register");
    $router->get("register/teamleader","Auth\RegisterController@teamLeader");
    $router->get("register/supervisor","Auth\RegisterController@supervisor");
    $router->post("login","Auth\LoginController@login");
    $router->get("register/supervisor/{id}","Auth\RegisterController@getSupervisor");
});


$router->group(['prefix' => 'api', 'middleware' => ['jwt.verify']], function () use ($router){
    $router->post("logout","Auth\LogoutController@logout");
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', "UserManagementController@index");
        $router->get('/request', "UserManagementController@userRequest");
        $router->get('/{id}', "UserManagementController@show");
        $router->post('/', "UserManagementController@store");
        $router->put('/{id}', "UserManagementController@update");
        $router->delete('/{id}', "UserManagementController@destroy");
        $router->post('/action', "UserManagementController@action");
    });
    $router->group(['prefix' => 'targetsales'], function () use ($router) {
        $router->get('/', "TargetSalesController@index");
        $router->get('/{id}', "TargetSalesController@show");
        $router->post('/', "TargetSalesController@store");
        $router->put('/{id}', "TargetSalesController@update");
    });    
    $router->group(['prefix' => 'cluster'], function () use ($router) {
        $router->get('/', "ClusterController@index");
        $router->get('/{id}', "ClusterController@show");
        $router->post('/', "ClusterController@store");
        $router->put('/{id}', "ClusterController@update");
        $router->delete('/{id}', "ClusterController@destroy");
    });    
    $router->group(['prefix' => 'property'], function () use ($router) {
        $router->get('/cluster', "PropertyController@cluster");
        $router->get('/', "PropertyController@index");
        $router->get('/{id}', "PropertyController@show");
        $router->post('/', "PropertyController@store");
        $router->post('/{id}', "PropertyController@update");
        $router->delete('/{id}', "PropertyController@destroy");
    });    
});
