<?php

use App\Console\Commands\CheckExpiredTasks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckExpiredTasks::class)->everyMinute();