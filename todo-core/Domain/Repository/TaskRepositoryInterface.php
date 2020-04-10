<?php

namespace TodoCore\Domain\Repository;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskNotFoundException;

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
    public function find($id): Task;

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
     */
    public function save(Task $task);
}