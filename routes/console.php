<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule sitemap generation every Sunday at 2:00 AM
Schedule::command('sitemap:generate')
    ->weeklyOn(0, '2:00')
    ->onSuccess(function () {
        info('Sitemap generated successfully');
    })
    ->onFailure(function () {
        report(new Exception('Sitemap generation failed'));
    });
