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

<<<<<<< HEAD
Route::get('my-apps', 'AppController@index');
=======
Route::view('/getting-started', 'templates.getting-started.index');
>>>>>>> b044a05d735b8b18e88a93e81c124c2ed03f8137

Auth::routes();
