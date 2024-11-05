<?php

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;

class CheckExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tasks for expired deadlines and mark them as failed if they have passed';

    /**
     * Execute the console command.
     */

     protected $taskService;
     public function __construct(TaskService $taskService)
    {
        parent::__construct();
        $this->taskService = $taskService;
    }
    public function handle()
    {
        // Запускаем проверку и обновление статуса просроченных задач
        $expiredTasksCount = $this->taskService->markExpiredTasksAsFailed();

        $this->info("{$expiredTasksCount} tasks were marked as failed due to expired deadlines.");
        
    }
}
