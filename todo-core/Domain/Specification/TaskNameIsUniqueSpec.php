<?php

namespace TodoCore\Domain\Specification;

use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskNameIsUniqueSpec
 *
 * Specification describes that Task's name should be unique
 *
 * @package TodoCore\Domain\Specification
 */
class TaskNameIsUniqueSpec implements SpecificationInterface
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $id;

    /**
     * TaskNameIsUniqueSpec constructor.
     *
     * @param TaskRepositoryInterface $repository
     * @param string $name
     * @param mixed $id
     */
    public function __construct(TaskRepositoryInterface $repository, string $name, $id = null)
    {
        $this->repository = $repository;
        $this->name = $name;
        $this->id = $id;
    }


    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        try {
            $task = $this->repository->findByName($this->name);
        } catch (TaskNotFoundException $e) {
            return true;
        }

        return $this->id == $task->getId();
    }
}
