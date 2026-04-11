<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup database + storage every day at 02:00 AM, keep 7 days
Schedule::command('app:backup --keep=7')->dailyAt('02:00');
