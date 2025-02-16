<?php

namespace App\Tests\Command;

use App\Command\UpdateTaskCommand;
use App\Service\TaskService;
use PHPUnit\Framework\TestCase;

class UpdateTaskCommandTest extends TestCase
{
    private TaskService $taskService;
    private string $testFilePath = 'test_tasks.json';

    protected function setUp(): void
    {
        $this->taskService = new TaskService($this->testFilePath);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testExecute()
    {
        $this->taskService->addTask("Buy groceries");
        $command = new UpdateTaskCommand($this->taskService);

        ob_start();
        $command->execute(1, "Buy groceries and cook dinner");
        $output = ob_get_clean();

        $this->assertEquals("Task updated successfully (ID: 1)\n", $output);
    }

    public function testExecuteWithInvalidId()
    {
        $command = new UpdateTaskCommand($this->taskService);

        ob_start();
        $command->execute(999, "Invalid task");
        $output = ob_get_clean();

        $this->assertEquals("Error: Task not found.\n", $output);
    }

}