<?php

use App\Http\Controllers\PageDashboardController;
use App\Http\Controllers\Pages\PageCourseDetailsController;
use App\Http\Controllers\Pages\PageHomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', PageHomeController::class)->name('pages.home');

Route::get('/courses/{course:slug}', PageCourseDetailsController::class)->name('pages.course-details');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', PageDashboardController::class)->name('dashboard');
});
