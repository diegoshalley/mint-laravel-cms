<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Cms\ContentController;
use App\Http\Controllers\Cms\AuditController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\EditorialCommentController;
use App\Http\Controllers\Cms\UserController;
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
Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/password/change', [PasswordController::class, 'edit'])->name('password.change');
    Route::put('/password/change', [PasswordController::class, 'update']);
    Route::get('/mfa/challenge', fn () => view('auth.mfa'))->name('mfa.challenge');
    Route::post('/mfa/challenge', [MfaController::class, 'challenge'])->middleware('throttle:5,1');
    Route::get('/cms/security/mfa', [MfaController::class, 'settings'])->name('mfa.settings');
    Route::post('/cms/security/mfa', [MfaController::class, 'enable'])->middleware('password.changed')->name('mfa.enable');
    Route::post('/cms/security/mfa/confirm', [MfaController::class, 'confirm'])->middleware('password.changed')->name('mfa.confirm');
    Route::delete('/cms/security/mfa', [MfaController::class, 'disable'])->middleware(['password.changed', 'mfa'])->name('mfa.disable');
});

Route::middleware(['auth', 'active', 'verified', 'password.changed', 'mfa.enrolled', 'mfa', 'cms.access'])
    ->prefix('cms')
    ->name('cms.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('content', ContentController::class)->except(['show', 'destroy']);
        Route::post('content/{content}/transition', [ContentController::class, 'transition'])->name('content.transition');
        Route::post('content/{content}/comments', [EditorialCommentController::class, 'store'])->name('content.comments.store');
        Route::post('content/{content}/revisions/{revision}/restore', [ContentController::class, 'restore'])->name('content.revisions.restore');
        Route::get('audit', AuditController::class)->name('audit.index');
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::post('users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
        Route::post('users/{user}/enable', [UserController::class, 'enable'])->name('users.enable');
    });
