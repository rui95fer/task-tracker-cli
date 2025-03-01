<?php

namespace App\Command;

use App\Service\TaskService;

class AddTaskCommand
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function execute($description): void
    {
        $id = $this->taskService->addTask($description);

        echo "Task added successfully (ID: $id)\n";
    }
}
