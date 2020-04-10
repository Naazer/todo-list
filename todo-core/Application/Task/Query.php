<?php

namespace TodoCore\Application\Task;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

/**
 * Class Query
 *
 * Sends query to TaskRepository to get objects
 *
 * @package TodoCore\Application\Task
 */
class Query
{
    /**
     * Task Repository
     *
     * @var TaskRepositoryInterface
     */
    protected $repository;

    /**
     * Query constructor
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get completed tasks
     *
     * @return array
     */
    public function getCompleted() : array
    {
        return $this->repository->findAllCompleted();
    }

    /**
     * Get uncompleted tasks
     *
     * @return array
     */
    public function getUncompleted() : array
    {
        return $this->repository->findAllUnCompleted();
    }

    /**
     * Get task by id
     *
     * @param string $id
     *
     * @return Task
     * @throws TaskNotFoundException
     */
    public function getById($id) : Task
    {
        try {
            $task = $this->repository->findById($id);
        } catch (TaskNotFoundException $e) {
            throw $e;
        }

        return $task;
    }
}