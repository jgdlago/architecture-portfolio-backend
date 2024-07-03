<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Laravel\Fortify\Http\Controllers\{AuthenticatedSessionController,
    RegisteredUserController,
    PasswordController,
    ProfileInformationController
};
use App\Http\Controllers\Auth\{
    EmailVerificationNotificationController,
    PasswordResetLinkController,
    LogoutController,
};


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
 * Auth Routes
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('/logout', [LogoutController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('logout');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware([
            'throttle:' . '6,1',
            'auth:sanctum'
        ]);

    Route::apiResources([
        'contact' => ContactController::class,
        'experience' => ExperienceController::class,
        'project' => ProjectController::class,
    ]);
});
