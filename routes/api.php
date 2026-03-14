<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PageVisitController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

Route::get('/home', [PublicContentController::class, 'home']);
Route::get('/about', [PublicContentController::class, 'about']);
Route::get('/project-categories', [PublicContentController::class, 'categories']);
Route::get('/projects', [PublicProjectController::class, 'index']);
Route::get('/projects/{slug}', [PublicProjectController::class, 'show']);
Route::post('/contact-messages', [ContactMessageController::class, 'store'])
    ->middleware('throttle:password-reset');
Route::post('/track-visit', [PageVisitController::class, 'store'])
    ->middleware('throttle:60,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('profile', ProfileController::class)->except('index');
    Route::apiResource('experience', ExperienceController::class);

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('dashboard/stats', [DashboardController::class, 'stats']);

        Route::post('upload', [FileUploadController::class, 'store']);
        Route::delete('upload', [FileUploadController::class, 'destroy']);

        Route::apiResource('project-categories', ProjectCategoryController::class)->except(['show']);
        Route::apiResource('projects', AdminProjectController::class);
        Route::get('projects/{project}/images', [ProjectImageController::class, 'index']);
        Route::post('projects/{project}/images', [ProjectImageController::class, 'store']);
        Route::put('projects/{project}/images/{projectImage}', [ProjectImageController::class, 'update']);
        Route::delete('projects/{project}/images/{projectImage}', [ProjectImageController::class, 'destroy']);

        Route::apiResource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('site-settings', [SiteSettingController::class, 'index']);
        Route::post('site-settings/upsert', [SiteSettingController::class, 'upsert']);
    });
});
