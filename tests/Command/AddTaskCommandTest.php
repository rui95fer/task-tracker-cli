<?php

namespace App\Tests\Command;

use App\Command\AddTaskCommand;
use App\Service\TaskService;
use PHPUnit\Framework\TestCase;

class AddTaskCommandTest extends TestCase
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
        $command = new AddTaskCommand($this->taskService);

        ob_start();
        $command->execute("Buy groceries");
        $output = ob_get_clean();

        $this->assertEquals("Task added successfully (ID: 1)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertCount(1, $tasks);
        $this->assertEquals("Buy groceries", $tasks[0]->getDescription());
        $this->assertEquals("todo", $tasks[0]->getStatus());
    }

    public function testExecuteWithMultipleTasks()
    {
        $command = new AddTaskCommand($this->taskService);

        ob_start();
        $command->execute("Buy groceries");
        ob_end_clean();

        ob_start();
        $command->execute("Write code");
        $output = ob_get_clean();

        $this->assertEquals("Task added successfully (ID: 2)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertCount(2, $tasks);
        $this->assertEquals("Buy groceries", $tasks[0]->getDescription());
        $this->assertEquals("Write code", $tasks[1]->getDescription());
    }
}