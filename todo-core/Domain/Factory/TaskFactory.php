<?php

namespace TodoCore\Domain\Factory;

use TodoCore\Domain\Entity\Task;

/**
 * Class TaskFactory
 * @package TodoCore\Domain\Factory
 */
class TaskFactory
{
    /**
     * Create Task object from name
     *
     * @param string $name
     * @return Task
     */
    public function create(string $name): Task
    {
        // Create Task object
        $task = new Task();
        $task->setName($name);

        return $task;
    }
}