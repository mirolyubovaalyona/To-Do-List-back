<?php

namespace App\Console\Commands;

use App\Services\TaskService;
use Illuminate\Console\Command;

class DeleteCompletedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-completed-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting completed tasks';

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
        $deletedTasksQuantity = $this->taskService->deleteCompletedTasks();

        $this->info("{$deletedTasksQuantity} tasks deleted.");
    }
}
