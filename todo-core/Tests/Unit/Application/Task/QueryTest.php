<?php

namespace TodoCore\Tests\Unit\Application\Task;

use PHPUnit\Framework\TestCase;
use TodoCore\Domain\Entity\Task;
use TodoCore\Application\Task\Query;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

class QueryTest extends TestCase
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCompletedPositive()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findAllCompleted')
            ->withAnyParameters()
            ->willReturn([]);

        $query = new Query($this->repositoryMock);
        $actual = $query->getCompleted();
        $this->assertIsArray($actual);
    }

    public function testGetUncompletedPositive()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findAllUnCompleted')
            ->withAnyParameters()
            ->willReturn([]);

        $query = new Query($this->repositoryMock);
        $actual = $query->getUncompleted();
        $this->assertIsArray($actual);
    }

    public function testGetByIdPositive()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willReturn(new Task());

        $query = new Query($this->repositoryMock);
        $actual = $query->getById(1);
        $this->assertInstanceOf(Task::class, $actual);
    }

    public function testGetByIdNegative()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willThrowException(new TaskNotFoundException());

        $query = new Query($this->repositoryMock);
        $this->expectException(TaskNotFoundException::class);
        $query->getById(1);
    }
}