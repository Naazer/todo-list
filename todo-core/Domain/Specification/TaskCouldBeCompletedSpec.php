<?php

namespace TodoCore\Domain\Specification;

use TodoCore\Domain\Entity\Task;

/**
 * Class TaskCouldBeCompletedSpec
 *
 * Only task with status in-progress can be completed
 *
 * @package TodoCore\Domain\Specification
 */
class TaskCouldBeCompletedSpec implements SpecificationInterface
{
    /**
     * @var Task
     */
    private $task;

    /**
     * TaskCanCompletedSpec constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return Task::STATUS_IN_PROGRESS == $this->task->getStatus();
    }
}