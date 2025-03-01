<?php

namespace App\Tests\Service;

use App\Service\TaskService;
use Exception;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
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

    public function testAddTask()
    {
        $id = $this->taskService->addTask("Buy groceries");
        $this->assertEquals(1, $id);
    }

    /**
     * @throws Exception
     */
    public function testUpdateTask()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->updateTask(1, "Buy groceries and cook dinner");

        $tasks = $this->taskService->listTasks();
        $this->assertEquals("Buy groceries and cook dinner", $tasks[0]->getDescription());
    }

    public function testDeleteTask()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->deleteTask(1);

        $tasks = $this->taskService->listTasks();
        $this->assertEmpty($tasks);
    }

    /**
     * @throws Exception
     */
    public function testMarkTask()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->markTask(1, "done");

        $tasks = $this->taskService->listTasks("done");
        $this->assertEquals("done", $tasks[0]->getStatus());
    }

    public function testListTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");

        $tasks = $this->taskService->listTasks();
        $this->assertCount(2, $tasks);
    }

}
