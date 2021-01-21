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
use Illuminate\Http\Request;

$router->get('/apiv2/users/{id}', ['middleware' => 'auth', function (Request $request, $id) {
    $user = Auth::user();

    $user = $request->user();

    //
}]);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    //user API routes
    $router->get('login/', 'UserController@authenticate');

    $router->get('users', ['uses' => 'UserController@showAllUsers']);

    $router->get('users/{id}', ['uses' => 'UserController@showUser']);

    $router->post('users', ['uses' => 'UserController@create']);

    $router->delete('users/{id}', ['uses' => 'UserController@delete']);

    $router->put('users/{id}', ['uses' => 'UserController@update']);

//group API routes
    $router->get('groups', ['uses' => 'GroupController@showAllGroups']);

    $router->get('groups/{id}', ['uses' => 'GroupController@showGroup']);

    $router->post('groups', ['uses' => 'GroupController@create']);

    $router->delete('groups/{id}', ['uses' => 'GroupController@delete']);

    $router->put('groups/{id}', ['uses' => 'GroupController@update']);

    $router->post('groups/{id}/members', ['uses' => 'GroupController@addMembers']);
    $router->get('groups/{id}/members', ['uses' => 'GroupController@showGroupMembers']);
    $router->delete('groups/{id}/members/{member_ids}', ['uses' => 'GroupController@removeGroupMember']);
    $router->post('groups/{id}/join-group', ['uses' => 'GroupController@joinGroup']);

});
