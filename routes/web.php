<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Cms\ContentController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::view('/services', 'public.services.index')->name('services.index');
Route::view('/agencies', 'public.agencies.index')->name('agencies.index');
Route::view('/news-notices', 'public.publications.index')->name('publications.index');
Route::view('/documents', 'public.documents.index')->name('documents.index');
Route::view('/contact', 'public.contact')->name('contact');

Route::middleware('guest')->group(function (): void {
    Route::get('/cms/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/cms/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::view('/mfa/challenge', 'auth.mfa')->middleware('auth')->name('mfa.challenge');

Route::middleware(['auth', 'verified', 'mfa', 'cms.access'])
    ->prefix('cms')
    ->name('cms.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('content', ContentController::class)->except(['show', 'destroy']);
        Route::post('content/{content}/transition', [ContentController::class, 'transition'])->name('content.transition');
    });
