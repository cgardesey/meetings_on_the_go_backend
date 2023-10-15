<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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

//Route::resource('users', 'UserController');
Route::get('/users', 'UserController@index')->middleware('auth:api');;
Route::get('/users/create', 'UserController@create')->middleware('auth:api');
Route::get('/users/{user}', 'UserController@show')->middleware('auth:api');
Route::post('/users', 'UserController@store')->middleware('auth:api');;
Route::get('/users/{user}/edit', 'UserController@edit');
Route::post('/users/{user}', 'UserController@update');
Route::patch('/update-confirmation-token/{user}', 'UserController@updateConfirmationToken')->middleware('auth:api');
Route::get('/users/{user}', 'UserController@destroy')->middleware('auth:api');

Route::get('/group-docs/{group}', 'GroupSessionController@groupDocs');

Route::post('/groups/{group}', 'GroupController@update');
Route::post('/create-user', 'UserController@createUser');
Route::post('/register', 'Auth\RegisterController@store');
Route::post('/login','Auth\LoginController@login');
Route::get('password/reset/{token}', 'Auth\RegisterController@resetPassword');

Route::post('/add-group-members', 'GroupController@addGroupMembers')->middleware('auth:api');
Route::post('/group-members/bulk-destroy', 'GroupMemberController@bulkDestroy');

Route::resource('/chats', 'ChatController')->middleware('auth:api');
Route::resource('/groups', 'GroupController')->middleware('auth:api');
Route::resource('/group-attendances', 'GroupAttendanceController')->middleware('auth:api');
Route::resource('/group-members', 'GroupMemberController')->middleware('auth:api');
Route::resource('/group-sessions', 'GroupSessionController')->middleware('auth:api');
Route::resource('/payments', 'PaymentController')->middleware('auth:api');
Route::resource('/subscription-plan', 'SubscriptionPlanController')->middleware('auth:api');
Route::post('/confirm-registration', 'UserController@confirmRegistration');

Route::post('/payments/pay', 'PaymentController@pay');

Route::get('/all-data', 'UserController@fetchAll')->middleware('auth:api');

Route::post('/room', 'PaymentController@getRoom')->middleware('auth:api');

Route::post('/check-subscription', 'PaymentController@checkSubscription')->middleware('auth:api');

Route::post('/payments/callback', 'PaymentController@callback');
