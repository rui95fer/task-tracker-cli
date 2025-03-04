# Task Tracker CLI

[![Project URL](https://img.shields.io/badge/Project-roadmap.sh%2Fprojects%2Ftask--tracker-blue)](https://roadmap.sh/projects/task-tracker)

A command-line interface (CLI) application to manage your tasks and to-do lists. Built with PHP and adhering to SOLID
principles.

---

## Features

- **Add, Update, and Delete tasks**
- **Mark tasks** as `todo`, `in-progress`, or `done`
- **List tasks** with filters for status (`all`, `todo`, `in-progress`, `done`)
- **Persistent storage** using a JSON file
- **Unit tests** for all components

---

## Installation

### Prerequisites

- PHP 8.0 or higher
- [Composer](https://getcomposer.org/)

### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/rui95fer/task-tracker-cli
   cd task-tracker-cli

2. Install dependencies:
   ```bash
   composer install

## Usage

Run commands using `php task-cli.php <command> [arguments]`.

### Commands

#### 1. **Add a Task**

      php task-cli.php add "Your task description"

      # Example: php task-cli.php add "Buy groceries"
      # Output: Task added successfully (ID: 1)

#### 2. **Update a Task**

      php task-cli.php update <task-id> "New description"

      # Example: php task-cli.php update 1 "Buy groceries and cook dinner"
      # Output: Task updated successfully (ID: 1)

#### 3. **Delete a Task**

      php task-cli.php delete <task-id>

      # Example: php task-cli.php delete 1
      # Output: Task deleted successfully (ID: 1)

#### 4. **Mark Task Status**

      php task-cli.php mark-in-progress <task-id>   # Mark as "in-progress"
      php task-cli.php mark-done <task-id>         # Mark as "done"

      # Example: php task-cli.php mark-done 2
      # Output: Task marked as done successfully (ID: 2)

#### 5. **List Tasks**

      php task-cli.php list                  # List all tasks
      php task-cli.php list <status>         # Filter by status (todo/in-progress/done)

      # Example: php task-cli.php list in-progress
      # Output:
      # ID: 2
      # Description: Write code
      # Status: in-progress

### Testing

Run the test suite with PHPUnit:

      ./vendor/bin/phpunit

---

🔗 **Project Roadmap**: [roadmap.sh/projects/task-tracker](https://roadmap.sh/projects/task-tracker)