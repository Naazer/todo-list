<?php

namespace TodoCore\Domain\Factory;

use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskFactory
 * @package TodoCore\Domain\Factory
 */
class TaskFactory
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $repository;

    /**
     * @var TaskValidator
     */
    protected $validator;

    /**
     * TaskFactory constructor.
     *
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->validator = new TaskValidator($this->repository);
    }


    /**
     * Create Task object from name
     *
     * @param string $name
     *
     * @return Task
     * @throws TaskNameEmptyException
     * @throws TaskNameExistedException
     */
    public function create(string $name): Task
    {
        try {
            $this->validator->validateName($name);
        } catch (TaskNameEmptyException|TaskNameExistedException $exception) {
            throw $exception;
        }

        // Create Task object
        $task = new Task();
        $task->setName($name);

        return $task;
    }
}