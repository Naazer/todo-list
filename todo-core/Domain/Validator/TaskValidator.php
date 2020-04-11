<?php

namespace TodoCore\Domain\Validator;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;
use TodoCore\Domain\Specification\TaskNameIsUniqueSpec;
use TodoCore\Domain\Specification\TaskCouldBeStartedSpec;
use TodoCore\Domain\Specification\TaskNameIsNotEmptySpec;
use TodoCore\Domain\Specification\TaskCouldBeCompletedSpec;

class TaskValidator
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $repository;

    /**
     * TaskValidator constructor.
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Validate Task name
     *
     * @param string $name
     * @param null $id
     *
     * @return bool
     * @throws TaskNameEmptyException
     * @throws TaskNameExistedException
     */
    public function validateName(string $name, $id = null): bool
    {
        $validateEmptySpec = new TaskNameIsNotEmptySpec($name);
        if (!$validateEmptySpec->isSatisfied()) {
            throw new TaskNameEmptyException('Task name is empty. Please specify name');
        }

        $validateUniqueSpec = new TaskNameIsUniqueSpec($this->repository, $name, $id);
        if (!$validateUniqueSpec->isSatisfied()) {
            throw new TaskNameExistedException(sprintf('Task name <%s> is already exist', $name));
        }

        return true;
    }

    /**
     * Validate ability to complete Task
     * Task can be completed only if it was in status In-Progress before
     *
     * @param Task $task
     *
     * @return bool
     * @throws TaskCompletionException
     */
    public function validateAbilityToCompleteTask(Task $task): bool
    {
        $spec = new TaskCouldBeCompletedSpec($task);
        if (!$spec->isSatisfied()) {
            $msg = sprintf('This task <%s> cannot be completed, as it must first be <%s>.', $task->getName(), Task::STATUS_IN_PROGRESS);
            throw new TaskCompletionException($msg);
        }

        return true;
    }

    /**
     * Validate ability to start Task
     * Task can be completed only:
     *  - if his status is in backlog now
     *  - if other tasks that are in progress are not found.
     *
     * @param Task $task
     *
     * @return bool
     * @throws TaskStartingException
     */
    public function validateAbilityToStartTask(Task $task): bool
    {
        $spec = new TaskCouldBeStartedSpec($this->repository, $task);
        if (!$spec->isSatisfied()) {
            $msg = sprintf('This task <%s> cannot be started.', $task->getName());
            throw new TaskStartingException($msg);
        }

        return true;
    }
}