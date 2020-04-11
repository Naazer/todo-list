<?php

namespace TodoCore\Application\Task;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Factory\TaskFactory;
use TodoCore\Domain\Exception\TaskNotFoundException;
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
     * Command constructor.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->factory = new TaskFactory();
    }

    /**
     * Create new task and save into repository
     *
     * @param string $name
     *
     * @return Task
     *
     * @throws TaskSavingException
     */
    public function createNewTask(string $name): Task
    {
        // Init Task object
        $task = $this->factory->create($name);

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
     */
    public function startTask($id): Task
    {
        // Find Task object from repository
        try {
            $task = $this->repository->findById($id);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // Update Task status to completed
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
     */
    public function completeTask($id) : Task
    {
        // Find Task object from repository
        try {
            $task = $this->repository->findById($id);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        // TODO: add validator about the task should be in In-Progress status before completed
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