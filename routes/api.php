<?php

use App\Http\Controllers\Api\Admin\DeveloperController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', '2fa'])->group(function () {
    Route::get('/recovery-codes', [UserController::class, 'getRecoveryCodes'])->name('api.recovery-codes');
});

Route::post('developers', [DeveloperController::class, 'getDevelopers'])
    ->middleware('api')
    ->name('developers.list');

