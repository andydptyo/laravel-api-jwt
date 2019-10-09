<?php

use Illuminate\Http\Request;
use App\User;

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

Route::resource('users', 'UserController')->middleware('jwt.verify');
Route::post('register', 'UserController@register')->name('api.register');
Route::post('login', 'UserController@login')->name('api.login');
