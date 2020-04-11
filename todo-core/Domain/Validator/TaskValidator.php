<?php

namespace TodoCore\Domain\Validator;

use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;
use TodoCore\Domain\Specification\TaskNameIsUniqueSpec;
use TodoCore\Domain\Specification\TaskNameIsNotEmptySpec;

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
    }
}