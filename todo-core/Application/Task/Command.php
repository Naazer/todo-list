<?php

namespace TodoCore\Application\Task;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Factory\TaskFactory;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;
use TodoCore\Application\Task\Exception\TaskSavingException;

/**
 * Class Command
 *
 * Executes Task's commands to create/update Task object from repository
 *
 * @package TodoCore\Application\Task
 */
class Command
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $repository;

    /**
     * TaskFactory
     *
     * @var TaskFactory
     */
    protected $factory;

    /**
     * @var TaskValidator
     */
    protected $validator;

    /**
     * Command constructor.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->validator = new TaskValidator($this->repository);
        $this->factory = new TaskFactory($this->repository, $this->validator);
    }

    /**
     * Create new task and save into repository
     *
     * @param string $name
     *
     * @return Task
     *
     * @throws TaskNameEmptyException
     * @throws TaskNameExistedException
     * @throws TaskSavingException
     */
    public function createNewTask(string $name): Task
    {
        // Init Task object
        try {
            $task = $this->factory->create($name);
        } catch (TaskNameExistedException|TaskNameEmptyException $exception) {
            throw $exception;
        }

        // Persist Task object into repository
        try {
            $this->repository->save($task);
        } catch (TaskSavingException $exception) {
            throw $exception;
        }

        return $task;
    }

    /**
     * Set task status to In-progress
     *
     * @param $id
     *
     * @return Task
     *
     * @throws TaskNotFoundException
     * @throws TaskSavingException
     * @throws TaskStartingException
     */
    public function startTask($id): Task
    {
        // Find Task object from repository
        try {
            $task = $this->repository->findById($id);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Validate ability to start task
        try {
            $this->validator->validateAbilityToStartTask($task);
        } catch (TaskStartingException $exception) {
            throw $exception;
        }

        // Update Task status to in-progress
        $task->setStatus(Task::STATUS_IN_PROGRESS);

        // Try to save Task object into repository
        try {
            $this->repository->save($task);
        } catch (TaskSavingException $exception) {
            throw $exception;
        }

        return $task;
    }

    /**
     * Set task status to completed
     *
     * @param $id
     *
     * @return Task
     *
     * @throws TaskNotFoundException
     * @throws TaskSavingException
     * @throws TaskCompletionException
     */
    public function completeTask($id) : Task
    {
        // Find Task object from repository
        try {
            $task = $this->repository->findById($id);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Validate ability to complete task
        try {
            $this->validator->validateAbilityToCompleteTask($task);
        } catch (TaskCompletionException $exception) {
            throw $exception;
        }

        // Update Task status to completed
        $task->setStatus(Task::STATUS_COMPLETED);

        // Try to save Task object into repository
        try {
            $this->repository->save($task);
        } catch (TaskSavingException $exception) {
            throw $exception;
        }

        return $task;
    }
}