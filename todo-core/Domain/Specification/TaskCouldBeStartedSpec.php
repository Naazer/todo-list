<?php

namespace TodoCore\Domain\Specification;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskCouldBeStartedSpec
 *
 * Specification describe that task could started (moved to in-progress status) only:
 *  - if his status is in backlog now
 *  - if other tasks that are in progress are not found.
 *
 * @package TodoCore\Domain\Specification
 */
class TaskCouldBeStartedSpec implements SpecificationInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private $repository;

    /**
     * @var Task
     */
    private $task;

    /**
     * TaskCouldBeStartedSpec constructor.
     *
     * @param TaskRepositoryInterface $repository
     * @param Task $task
     */
    public function __construct(TaskRepositoryInterface $repository, Task $task)
    {
        $this->repository = $repository;
        $this->task = $task;
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        // If status of Task which we want to start is not backlog
        if (Task::STATUS_BACKLOG != $this->task->getStatus()) {
            return false;
        }

        // if we found in-progress Task then it is not accept to our criteria because only one in-progress task can be
        try {
            $this->repository->findTaskInProgress();
        } catch (TaskNotFoundException $exception) {
            return true;
        }

        return false;
    }
}