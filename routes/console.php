<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:mint', function (): void {
    $this->info('Ministry of the Interior CMS');
    $this->line('Public website and governed content management system.');
})->purpose('Display Ministry CMS application information');
