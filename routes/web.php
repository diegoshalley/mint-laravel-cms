<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');
Route::view('/about', 'public.about.index')->name('about.index');
Route::view('/services', 'public.services.index')->name('services.index');
Route::view('/agencies', 'public.agencies.index')->name('agencies.index');
Route::view('/news-notices', 'public.publications.index')->name('publications.index');
Route::view('/documents', 'public.documents.index')->name('documents.index');
Route::view('/contact', 'public.contact')->name('contact');

Route::middleware(['auth', 'verified', 'mfa', 'cms.access'])
    ->prefix('cms')
    ->name('cms.')
    ->group(function (): void {
        Route::view('/', 'cms.dashboard')->name('dashboard');
    });
