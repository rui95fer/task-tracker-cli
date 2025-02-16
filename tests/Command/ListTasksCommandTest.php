<?php

namespace App\Tests\Command;

use App\Command\ListTasksCommand;
use App\Service\TaskService;
use Exception;
use PHPUnit\Framework\TestCase;

class ListTasksCommandTest extends TestCase
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

    /**
     * @throws Exception
     */
    public function testExecuteAllListTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");
        $this->taskService->addTask("Read a book");
        $this->taskService->markTask(2, "in-progress");
        $this->taskService->markTask(3, "done");

        $command = new ListTasksCommand($this->taskService);

        ob_start();
        $command->execute();
        $output = ob_get_clean();

        $this->assertStringContainsString("ID: 1", $output);
        $this->assertStringContainsString("Description: Buy groceries", $output);
        $this->assertStringContainsString("Status: todo", $output);

        $this->assertStringContainsString("ID: 2", $output);
        $this->assertStringContainsString("Description: Write code", $output);
        $this->assertStringContainsString("Status: in-progress", $output);

        $this->assertStringContainsString("ID: 3", $output);
        $this->assertStringContainsString("Description: Read a book", $output);
        $this->assertStringContainsString("Status: done", $output);
    }

    /**
     * @throws Exception
     */
    public function testExecuteListTodoTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");
        $this->taskService->addTask("Read a book");
        $this->taskService->markTask(2, "in-progress");
        $this->taskService->markTask(3, "done");

        $command = new ListTasksCommand($this->taskService);

        ob_start();
        $command->execute('todo');
        $output = ob_get_clean();

        $this->assertStringContainsString("ID: 1", $output);
        $this->assertStringContainsString("Description: Buy groceries", $output);
        $this->assertStringContainsString("Status: todo", $output);

        $this->assertStringNotContainsString("ID: 2", $output);
        $this->assertStringNotContainsString("Description: Write code", $output);
        $this->assertStringNotContainsString("Status: in-progress", $output);

        $this->assertStringNotContainsString("ID: 3", $output);
        $this->assertStringNotContainsString("Description: Read a book", $output);
        $this->assertStringNotContainsString("Status: done", $output);
    }

    /**
     * @throws Exception
     */
    public function testExecuteListInProgressTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");
        $this->taskService->addTask("Read a book");
        $this->taskService->markTask(2, "in-progress");
        $this->taskService->markTask(3, "done");

        $command = new ListTasksCommand($this->taskService);

        ob_start();
        $command->execute('in-progress');
        $output = ob_get_clean();

        $this->assertStringNotContainsString("ID: 1", $output);
        $this->assertStringNotContainsString("Description: Buy groceries", $output);
        $this->assertStringNotContainsString("Status: todo", $output);

        $this->assertStringContainsString("ID: 2", $output);
        $this->assertStringContainsString("Description: Write code", $output);
        $this->assertStringContainsString("Status: in-progress", $output);

        $this->assertStringNotContainsString("ID: 3", $output);
        $this->assertStringNotContainsString("Description: Read a book", $output);
        $this->assertStringNotContainsString("Status: done", $output);
    }

    /**
     * @throws Exception
     */
    public function testExecuteListDoneTasks()
    {
        $this->taskService->addTask("Buy groceries");
        $this->taskService->addTask("Write code");
        $this->taskService->addTask("Read a book");
        $this->taskService->markTask(2, "in-progress");
        $this->taskService->markTask(3, "done");

        $command = new ListTasksCommand($this->taskService);

        ob_start();
        $command->execute('done');
        $output = ob_get_clean();

        $this->assertStringNotContainsString("ID: 1", $output);
        $this->assertStringNotContainsString("Description: Buy groceries", $output);
        $this->assertStringNotContainsString("Status: todo", $output);

        $this->assertStringNotContainsString("ID: 2", $output);
        $this->assertStringNotContainsString("Description: Write code", $output);
        $this->assertStringNotContainsString("Status: in-progress", $output);

        $this->assertStringContainsString("ID: 3", $output);
        $this->assertStringContainsString("Description: Read a book", $output);
        $this->assertStringContainsString("Status: done", $output);
    }

    public function testExecuteListNoTasks()
    {
        $command = new ListTasksCommand($this->taskService);

        ob_start();
        $command->execute();
        $output = ob_get_clean();

        $this->assertEquals("No tasks found.\n", $output);
    }


}