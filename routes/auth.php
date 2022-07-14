<?php

use App\Http\Controllers\Auth\AuthenticatedSessionDashboardController;
use App\Http\Controllers\Auth\ConfirmablePasswordDashboardController;
use App\Http\Controllers\Auth\EmailVerificationNotificationDashboardController;
use App\Http\Controllers\Auth\EmailVerificationPromptDashboardController;
use App\Http\Controllers\Auth\NewPasswordDashboardController;
use App\Http\Controllers\Auth\PasswordResetLinkDashboardController;
use App\Http\Controllers\Auth\RegisteredUserDashboardController;
use App\Http\Controllers\Auth\VerifyEmailDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserDashboardController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserDashboardController::class, 'store']);

    Route::get('login', [AuthenticatedSessionDashboardController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionDashboardController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkDashboardController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkDashboardController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordDashboardController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordDashboardController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptDashboardController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailDashboardController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationDashboardController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordDashboardController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordDashboardController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionDashboardController::class, 'destroy'])
                ->name('logout');
});
