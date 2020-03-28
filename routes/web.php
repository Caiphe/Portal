<?php

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
});

Route::get('apps', 'AppController@index');
Route::get('apps/create', 'AppController@create');
Route::post('apps', 'AppController@store');
Route::get('apps/{id}/edit', 'AppController@edit');
Route::put('apps/{id}', 'AppController@update');
Route::delete('apps', 'AppController@destroy');

Route::view('/getting-started', 'templates.getting-started.index');

Auth::routes();
