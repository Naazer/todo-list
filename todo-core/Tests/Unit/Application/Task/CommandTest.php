<?php

namespace TodoCore\Tests\Unit\Application\Task;

use PHPUnit\Framework\TestCase;
use TodoCore\Domain\Entity\Task;
use TodoCore\Application\Task\Command;
use TodoCore\Domain\Factory\TaskFactory;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;
use TodoCore\Application\Task\Exception\TaskSavingException;

class CommandTest extends TestCase
{
    /**
     * TaskRepository
     *
     * @var TaskRepositoryInterface
     */
    protected $repositoryMock;

    /**
     * TaskFactory
     *
     * @var TaskFactory
     */
    protected $factoryMock;

    /**
     * @var TaskValidator
     */
    protected $validatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factoryMock = $this->getMockBuilder(TaskFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validatorMock = $this->getMockBuilder(TaskValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreateNewTaskPositive()
    {
        $name = "Buy a car";
        $task = new Task();
        $task->setName($name);

        $this->factoryMock
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willReturn($task);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $actual = $command->createNewTask($name);
        $this->assertEquals($name, $actual->getName());
    }

    public function testCreateNewTaskNegativeTaskNameEmptyException()
    {
        $this->factoryMock
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willThrowException(new TaskNameEmptyException());

        $this->repositoryMock
            ->expects($this->never())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskNameEmptyException::class);
        $command->createNewTask("Test");
    }

    public function testCreateNewTaskNegativeTaskNameExistedException()
    {
        $this->factoryMock
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willThrowException(new TaskNameExistedException());

        $this->repositoryMock
            ->expects($this->never())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskNameExistedException::class);
        $command->createNewTask("Test");
    }

    public function testCreateNewTaskNegativeTaskSavingException()
    {
        $this->factoryMock
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willReturn(new Task());

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willThrowException(new TaskSavingException());

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskSavingException::class);
        $command->createNewTask("Test");
    }

    public function testStartTaskPositive()
    {
        $task = new Task();
        $task->setName("Buy a car");

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willReturn($task);

        $this->validatorMock
            ->expects($this->once())
            ->method('validateAbilityToStartTask')
            ->withAnyParameters()
            ->willReturn(true);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $actual = $command->startTask(1);
        $this->assertEquals($actual->getStatus(), Task::STATUS_IN_PROGRESS);
    }

    public function testStartTaskNegativeTaskNotFoundException()
    {
        $task = new Task();
        $task->setName("Buy a car");

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willThrowException(new TaskNotFoundException());

        $this->validatorMock
            ->expects($this->never())
            ->method('validateAbilityToStartTask')
            ->withAnyParameters();

        $this->repositoryMock
            ->expects($this->never())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskNotFoundException::class);
        $command->startTask(1);
    }

    public function testStartTaskNegativeTaskStartingException()
    {
        $task = new Task();
        $task->setName("Buy a car");

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willReturn($task);

        $this->validatorMock
            ->expects($this->once())
            ->method('validateAbilityToStartTask')
            ->withAnyParameters()
            ->willThrowException(new TaskStartingException());

        $this->repositoryMock
            ->expects($this->never())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskStartingException::class);
        $command->startTask(1);
    }

    public function testCompleteTaskPositive()
    {
        $task = new Task();
        $task->setName("Buy a car");

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willReturn($task);

        $this->validatorMock
            ->expects($this->once())
            ->method('validateAbilityToCompleteTask')
            ->withAnyParameters()
            ->willReturn(true);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $actual = $command->completeTask(1);
        $this->assertEquals($actual->getStatus(), Task::STATUS_COMPLETED);
    }

    public function testStartTaskNegativeTaskCompletionException()
    {
        $task = new Task();
        $task->setName("Buy a car");

        $this->repositoryMock
            ->expects($this->once())
            ->method('findById')
            ->withAnyParameters()
            ->willReturn($task);

        $this->validatorMock
            ->expects($this->once())
            ->method('validateAbilityToCompleteTask')
            ->withAnyParameters()
            ->willThrowException(new TaskCompletionException());

        $this->repositoryMock
            ->expects($this->never())
            ->method('save')
            ->withAnyParameters();

        $command = new Command($this->repositoryMock, $this->validatorMock, $this->factoryMock);
        $this->expectException(TaskCompletionException::class);
        $command->completeTask(1);
    }
}