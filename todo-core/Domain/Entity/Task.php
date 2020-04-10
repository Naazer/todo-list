<?php

namespace TodoCore\Domain\Entity;

/**
 * Class Task
 * @package TodoCore\Domain\Entity
 */
class Task
{
    /**
     * Statuses
     */
    const STATUS_BACKLOG = 'backlog';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_COMPLETED = 'completed';

    /**
     * ID
     *
     * @var mixed
     */
    private $id;

    /**
     * Name
     *
     * @var string
     */
    private $name;

    /**
     * Status
     * Default is backlog
     *
     * @var string
     */
    private $status = Task::STATUS_BACKLOG;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}