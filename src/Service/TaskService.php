<?php

namespace App\Service;

use App\Model\Task;
use Exception;

class TaskService
{
    private string $filePath;

    public function __construct($filePath = 'tasks.json')
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    private function loadTasks(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $tasksData = json_decode(file_get_contents($this->filePath), true);

        if ($tasksData === null) {
            return [];
        }

        $tasks = [];

        foreach ($tasksData as $taskData) {
            $tasks[] = new Task(
                $taskData['id'],
                $taskData['description'],
                $taskData['status'],
                $taskData['createdAt'],
                $taskData['updatedAt']
            );
        }

        return $tasks;
    }

    private function saveTasks(array $tasks): void
    {
        $tasksData = array_map(function ($task) {
            return [
                'id' => $task->getId(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'createdAt' => $task->getCreatedAt(),
                'updatedAt' => $task->getUpdatedAt(),
            ];
        }, $tasks);

        file_put_contents($this->filePath, json_encode($tasksData, JSON_PRETTY_PRINT));
    }

    public function addTask($description)
    {
        $tasks = $this->loadTasks();

        $newId = empty($tasks) ? 1 : max(array_map(function ($task) {
            return $task->getId();
        }, $tasks)) + 1;

        $tasks[] = new Task($newId, $description, 'todo', date('d-m-Y H:i:s'), date('d-m-Y H:i:s'));

        $this->saveTasks($tasks);

        return $newId;
    }

    /**
     * @throws Exception
     */
    public function updateTask($id, $description): void
    {
        $tasks = $this->loadTasks();

        foreach ($tasks as $task) {
            if ($task->getId() == $id) {
                $task->setDescription($description);
                $task->setUpdatedAt(date('d-m-Y H:i:s'));
                $this->saveTasks($tasks);
                return;
            }
        }

        throw new Exception("Task not found.");
    }

    /**
     * @throws Exception
     */
    public function deleteTask($id): void
    {
        $tasks = $this->loadTasks();

        $taskExists = false;

        foreach ($tasks as $task) {
            if ($task->getId() == $id) {
                $taskExists = true;
                break;
            }
        }

        if (!$taskExists) {
            throw new Exception("Task not found.");
        }

        $tasks = array_filter($tasks, function ($task) use ($id) {
            return $task->getId() != $id;
        });

        $tasks = array_values($tasks);

        $this->saveTasks($tasks);
    }

    /**
     * @throws Exception
     */
    public function markTask($id, $status): void
    {
        $validStatuses = ['todo', 'in-progress', 'done'];

        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status. Status must be 'todo', 'in-progress', or 'done'.");
        }

        $tasks = $this->loadTasks();

        foreach ($tasks as $task) {
            if ($task->getId() == $id) {
                $task->setStatus($status);

                $task->setUpdatedAt(date('d-m-Y H:i:s'));

                $this->saveTasks($tasks);

                return;
            }
        }

        throw new Exception("Task not found.");
    }

    public function listTasks($status = null): array
    {
        $tasks = $this->loadTasks();

        if ($status) {
            $tasks = array_filter($tasks, function ($task) use ($status) {
                return $task->getStatus() == $status;
            });
        }

        return $tasks;
    }
}
