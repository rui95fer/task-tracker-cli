<?php

require 'src/Model/Task.php';
require 'src/Service/TaskService.php';
require 'src/Command/AddTaskCommand.php';
require 'src/Command/UpdateTaskCommand.php';
require 'src/Command/DeleteTaskCommand.php';
require 'src/Command/MarkTaskCommand.php';
require 'src/Command/ListTasksCommand.php';

use App\Command\AddTaskCommand;
use App\Command\UpdateTaskCommand;
use App\Command\DeleteTaskCommand;
use App\Command\MarkTaskCommand;
use App\Command\ListTasksCommand;
use App\Service\TaskService;

$taskService = new TaskService();

$action = $argv[1] ?? null;

try {

    switch ($action) {
        case 'add':
            $command = new AddTaskCommand($taskService);
            $command->execute($argv[2]);
            break;

        case 'update':
            $command = new UpdateTaskCommand($taskService);
            $command->execute($argv[2], $argv[3]);
            break;

        case 'delete':
            $command = new DeleteTaskCommand($taskService);
            $command->execute($argv[2]);
            break;

        case 'mark-in-progress':
        case 'mark-done':
            $command = new MarkTaskCommand($taskService);
            $command->execute($argv[2], str_replace('mark-', '', $action));
            break;

        case 'list':
            $command = new ListTasksCommand($taskService);
            $status = $argv[2] ?? null;
            $command->execute($status);
            break;

        default:
            echo "Invalid command.\n";
            break;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}