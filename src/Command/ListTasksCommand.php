<?php

namespace App\Command;

use App\Service\TaskService;

class ListTasksCommand
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function execute($status = null): void
    {
        $tasks = $this->taskService->listTasks($status);

        if (empty($tasks)) {
            echo "No tasks found.\n";
            return;
        }

        foreach ($tasks as $task) {
            echo "ID: " . $task->getId() . "\n";
            echo "Description: " . $task->getDescription() . "\n";
            echo "Status: " . $task->getStatus() . "\n";
            echo "Created At: " . $task->getCreatedAt() . "\n";
            echo "Updated At: " . $task->getUpdatedAt() . "\n\n";
        }
    }
}