<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    // $api->get('/', function() {
    //     return ['Fruits' => 'Delicious and healthy!'];
    // });

    $api->get('fruits', 'App\Http\Controllers\FruitsController@index');
    $api->get('fruit/{id}', 'App\Http\Controllers\FruitsController@show');


    $api->post('authenticate', 'App\Http\Controllers\AuthenticateController@authenticate');
	$api->post('logout', 'App\Http\Controllers\AuthenticateController@logout');
	$api->get('token', 'App\Http\Controllers\AuthenticateController@getToken');
});


// attached api.auth middleware to the new Dingo version group so we are 
// telling dingo to use JWT authentication to protect these routes.
$api->version('v1', ['middleware' => 'api.auth'], function ($api) {

    $api->get('authenticated_user', 'App\Http\Controllers\AuthenticateController@authenticatedUser');

    $api->post('fruits', 'App\Http\Controllers\FruitsController@store');
    $api->delete('fruits/{id}', 'App\Http\Controllers\FruitsController@destroy');
});
