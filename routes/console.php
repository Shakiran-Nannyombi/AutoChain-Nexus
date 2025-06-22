<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily backup at 2:00 AM
Artisan::command('schedule:backup', function () {
    $this->info('Scheduling daily backup...');
    // This will be handled by the system's cron job
})->purpose('Schedule daily backup task');
