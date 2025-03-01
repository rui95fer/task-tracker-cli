<?php

namespace App\Command;

use App\Service\TaskService;
use Exception;

class MarkTaskCommand
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function execute($id, $status): void
    {
        try {
            $this->taskService->markTask($id, $status);
            echo "Task marked as $status successfully (ID: $id)\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

}
