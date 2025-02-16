<?php

namespace App\Command;

use App\Service\TaskService;
use Exception;

class UpdateTaskCommand
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function execute($id, $description): void
    {
        try {
            $this->taskService->updateTask($id, $description);
            echo "Task updated successfully (ID: $id)\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

}