<?php

namespace TodoCore\Infrastructure\Database\Doctrine\Repository;

use TodoCore\Domain\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;
use TodoCore\Application\Task\Exception\TaskSavingException;

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
    public function findTaskInProgress(): Task
    {
        /** @var Task $task */
        $task = $this->findOneBy(['status' => Task::STATUS_IN_PROGRESS]);

        if (null === $task) {
            throw new TaskNotFoundException("Task with status in-progress not found");
        }

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name): Task
    {
        /** @var Task $task */
        $task = $this->findOneBy(['name' => $name]);

        if (null === $task) {
            throw new TaskNotFoundException("Task with name: <$name> not found");
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
        $expression = Criteria::expr()->in('status', [Task::STATUS_BACKLOG, Task::STATUS_IN_PROGRESS]);

        return $this->createQueryBuilder('task')
            ->andWhere($expression)
            ->getQuery()
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function save(Task $task)
    {
        try {
            $this->getEntityManager()->persist($task);
        } catch (ORMInvalidArgumentException $e) {
            throw new TaskSavingException($e->getMessage());
        }

        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new TaskSavingException($e->getMessage());
        }


        return true;
    }

}