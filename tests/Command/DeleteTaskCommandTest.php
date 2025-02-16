<?php

namespace App\Tests\Command;

use App\Command\DeleteTaskCommand;
use App\Service\TaskService;
use PHPUnit\Framework\TestCase;

class DeleteTaskCommandTest extends TestCase
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

        $command = new DeleteTaskCommand($this->taskService);

        ob_start();
        $command->execute(1);
        $output = ob_get_clean();

        $this->assertEquals("Task deleted successfully (ID: 1)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertCount(0, $tasks);
    }

    public function testExecuteWithInvalidId()
    {
        $command = new DeleteTaskCommand($this->taskService);

        ob_start();
        $command->execute(999);
        $output = ob_get_clean();

        $this->assertEquals("Error: Task not found.\n", $output);
    }

    public function testExecuteWithMultipleTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");

        $command = new DeleteTaskCommand($this->taskService);

        ob_start();
        $command->execute(1);
        ob_end_clean();

        $tasks = $this->taskService->listTasks();
        $this->assertCount(1, $tasks);
        $this->assertEquals("Write code", $tasks[0]->getDescription());

        ob_start();
        $command->execute(2);
        $output = ob_get_clean();

        $this->assertEquals("Task deleted successfully (ID: 2)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertCount(0, $tasks);
    }

}