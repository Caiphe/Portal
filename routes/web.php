<?php

Auth::loginUsingId(1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.sidebar');
})->name('home');

Route::view('/getting-started', 'templates.getting-started.index');

Route::get('profile', 'UserController@show')->name('user.profile');
Route::put('profile/{user}/update', 'UserController@update')->name('user.profile.update');
Route::post('profile/update/picture', 'UserController@updateProfilePicture')->name('user.profile.update.picture');

Auth::routes();
