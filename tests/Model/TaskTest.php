<?php

namespace App\Tests\Model;

use App\Model\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskCreation()
    {
        $task = new Task(1, "Buy groceries", "todo", date('d-m-Y H:i:s'), date('d-m-Y H:i:s'));
        $this->assertEquals(1, $task->getId());
        $this->assertEquals("Buy groceries", $task->getDescription());
        $this->assertEquals("todo", $task->getStatus());
        $this->assertEquals(date('d-m-Y H:i:s'), $task->getCreatedAt());
        $this->assertEquals(date('d-m-Y H:i:s'), $task->getUpdatedAt());
    }

    public function testTaskStatusUpdate()
    {
        $task = new Task(1, "Buy groceries", "todo", date('d-m-Y H:i:s'), date('d-m-Y H:i:s'));
        $task->setStatus("in-progress");
        $this->assertEquals("in-progress", $task->getStatus());
    }
}
