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

Route::view('/getting-started', 'templates.getting-started.index');
Route::view('/request-approval', 'templates.getting-started.show');

Auth::routes();
