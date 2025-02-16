<?php

namespace App\Tests\Command;

use App\Command\MarkTaskCommand;
use App\Service\TaskService;
use Exception;
use PHPUnit\Framework\TestCase;

class MarkTaskCommandTest extends TestCase
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

    public function testExecuteMarkTaskAsInProgress()
    {
        $this->taskService->addTask("Buy groceries");

        $command = new MarkTaskCommand($this->taskService);

        ob_start();
        $command->execute(1, "in-progress");
        $output = ob_get_clean();

        $this->assertEquals("Task marked as in-progress successfully (ID: 1)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertEquals("in-progress", $tasks[0]->getStatus());
    }

    public function testExecuteMarkTaskAsDone()
    {
        $this->taskService->addTask("Buy groceries");

        $command = new MarkTaskCommand($this->taskService);

        ob_start();
        $command->execute(1, "done");
        $output = ob_get_clean();

        $this->assertEquals("Task marked as done successfully (ID: 1)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertEquals("done", $tasks[0]->getStatus());
    }

    /**
     * @throws Exception
     */
    public function testExecuteMarkTaskAsTodo()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->markTask(1, "in-progress");

        $command = new MarkTaskCommand($this->taskService);

        ob_start();
        $command->execute(1, "todo");
        $output = ob_get_clean();

        $this->assertEquals("Task marked as todo successfully (ID: 1)\n", $output);

        $tasks = $this->taskService->listTasks();
        $this->assertEquals("todo", $tasks[0]->getStatus());
    }

    public function testExecuteMarkTaskAsInvalidId()
    {
        $command = new MarkTaskCommand($this->taskService);

        ob_start();
        $command->execute(999, "in-progress");
        $output = ob_get_clean();

        $this->assertEquals("Error: Task not found.\n", $output);
    }

    public function testExecuteMarkTaskWithInvalidStatus()
    {
        $this->taskService->addTask("Buy groceries");

        $command = new MarkTaskCommand($this->taskService);

        ob_start();
        $command->execute(1, "invalid-status");
        $output = ob_get_clean();

        $this->assertEquals("Error: Invalid status. Status must be 'todo', 'in-progress', or 'done'.\n", $output);
    }


}