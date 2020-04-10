<?php

namespace TodoCore\Infrastructure\Database\Doctrine\Repository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TodoCore\Domain\Entity\Task;
use Doctrine\ORM\EntityRepository;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

/**
 * Class TaskRepository
 * @package TodoCore\Infrastructure\Database\Doctrine\Repository
 */
class TaskRepository extends EntityRepository implements TaskRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findById($id): Task
    {
        /** @var Task $task */
        $task = $this->findById($id);

        if (null === $task) {
            throw new TaskNotFoundException("Task with ID: $id doesnt exist");
        }

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @inheritDoc
     */
    public function findAllCompleted()
    {
        return $this->findBy(['status' => Task::STATUS_COMPLETED]);
    }

    /**
     * @inheritDoc
     */
    public function findAllUnCompleted()
    {
        return $this->findBy(['status' => Task::STATUS_BACKLOG]);
    }

    /**
     * @inheritDoc
     */
    public function save(Task $task)
    {
        try {
            $this->getEntityManager()->persist($task);
        } catch (ORMInvalidArgumentException $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new \Exception($e->getMessage());
        }


        return true;
    }

}