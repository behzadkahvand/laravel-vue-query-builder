<?php

use App\Http\Controllers\PostController;
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

Route::controller(PostController::class)->prefix("store")->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'view');
    Route::match(['post', 'patch'], '/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});


