<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', static function () {
    return view('welcome');
});

Route::middleware('auth')
    ->name('dashboard.')
    ->controller(DashboardController::class)
    ->group(static function () {
        Route::get('/dashboard', 'view')->name('view');
    });

require __DIR__.'/auth.php';
