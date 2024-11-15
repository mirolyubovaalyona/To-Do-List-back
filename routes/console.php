<?php

use App\Console\Commands\CheckExpiredTasks;
use App\Console\Commands\DeleteCompletedTasks;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckExpiredTasks::class)->everyMinute();
Schedule::command(DeleteCompletedTasks::class)->everyMinute();