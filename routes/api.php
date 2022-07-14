<?php

use App\Http\Controllers\Api\ApiAuthDashboardController;
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
Route::middleware('api')
    ->group(static function () {
        Route::post('login', [ApiAuthDashboardController::class, 'login'])->name('login');

        Route::middleware('auth:api')
            ->group(static function () {
                Route::controller(ApiAuthDashboardController::class)
                    ->group(static function () {
                        Route::post('logout', 'logout')->name('logout');
                        Route::post('refresh', 'refresh')->name('refresh');
                        Route::post('me', 'me')->name('me');
                    });
            });
    });
