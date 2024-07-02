<?php

use Illuminate\Support\Facades\Route;
use \Laravel\Fortify\Http\Controllers\{AuthenticatedSessionController,
    RegisteredUserController,
    PasswordController,
    ProfileInformationController
};
use App\Http\Controllers\Auth\{
    PasswordResetLinkController,
    LogoutController,
};

/*
 * Auth Routes
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.reset');
    Route::post('/logout', [LogoutController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('logout');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware([
            'throttle:'.'6,1',
            'auth:sanctum'
        ]);
});
