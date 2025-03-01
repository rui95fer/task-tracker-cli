<?php

namespace App\Command;

use App\Service\TaskService;
use Exception;

class DeleteTaskCommand
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function execute($id): void
    {
        try {
            $this->taskService->deleteTask($id);
            echo "Task deleted successfully (ID: $id)\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
