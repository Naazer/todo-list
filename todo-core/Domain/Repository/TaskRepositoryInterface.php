<?php

namespace TodoCore\Domain\Repository;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Application\Task\Exception\TaskSavingException;

/**
 * Interface TaskRepositoryInterface
 * @package TodoCore\Domain\Repository
 */
interface TaskRepositoryInterface
{
    /**
     * Find a Task from given ID
     *
     * @param mixed $id
     *
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findById($id): Task;

    /**
     * Find a Task by name
     *
     * @param string $name
     *
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findByName(string $name): Task;

    /**
     * Find a Task with status in-progress
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findTaskInProgress(): Task;

    /**
     * Find a Tasks with status backlog
     * @return array
     */
    public function findBacklogTasks(): array;

    /**
     * Find all tasks
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Find all completed tasks
     *
     * @return array
     */
    public function findAllCompleted();

    /**
     * Find all uncompleted tasks
     * With statuses: backlog and in-progress
     *
     * @return array
     */
    public function findAllUnCompleted();

    /**
     * Save Task into repository
     *
     * @param Task $task
     *
     * @return void
     * @throws TaskSavingException
     */
    public function save(Task $task);
}