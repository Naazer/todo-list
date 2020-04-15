<?php

namespace TodoCore\Tests\Unit\Domain\Validator;

use PHPUnit\Framework\TestCase;
use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Exception\TaskNotFoundException;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskStartingException;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskCompletionException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

class TaskValidatorTest extends TestCase
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

    public function testValidateNamePositive()
    {
        /*
         * Prepare mock
         */
        $this->repositoryMock
            ->expects($this->once())
            ->method('findByName')
            ->withAnyParameters()
            ->willReturn(new Task());

        $name = "Make a cup of tea";

        $taskValidator = new TaskValidator($this->repositoryMock);
        $valid = $taskValidator->validateName($name);

        $this->assertTrue($valid);
    }

    public function testValidateNameNegativeNameEmpty()
    {
        $name = "";

        $taskValidator = new TaskValidator($this->repositoryMock);

        $this->expectException(TaskNameEmptyException::class);
        $taskValidator->validateName($name);
    }

    public function testValidateNameNegativeNameUnique()
    {
        /*
         * Prepare mocks
         */
        $this->repositoryMock
            ->expects($this->once())
            ->method('findByName')
            ->withAnyParameters()
            ->willReturn(new Task());

        $name = "Test name";

        $taskValidator = new TaskValidator($this->repositoryMock);

        $this->expectException(TaskNameExistedException::class);
        $taskValidator->validateName($name, 1);
    }

    public function testValidateAbilityToCompleteTaskPositive()
    {
        $taskValidator = new TaskValidator($this->repositoryMock);

        $task = new Task();
        $task->setName('Test name');
        $task->setStatus(Task::STATUS_IN_PROGRESS);

        $valid = $taskValidator->validateAbilityToCompleteTask($task);

        $this->assertTrue($valid);
    }

    public function testValidateAbilityToCompleteTaskNegative()
    {
        $taskValidator = new TaskValidator($this->repositoryMock);

        $task = new Task();
        $task->setName('Test name');

        $this->expectException(TaskCompletionException::class);
        $taskValidator->validateAbilityToCompleteTask($task);
    }

    public function testValidateAbilityToStartTaskPositive()
    {
        /*
         * Prepare mocks
         */
        $this->repositoryMock
            ->expects($this->once())
            ->method('findTaskInProgress')
            ->withAnyParameters()
            ->willThrowException(new TaskNotFoundException());

        $taskValidator = new TaskValidator($this->repositoryMock);

        $task = new Task();
        $task->setName('Test name');

        $valid = $taskValidator->validateAbilityToStartTask($task);

        $this->assertTrue($valid);
    }

    public function testValidateAbilityToStartTaskNegative()
    {
        $taskValidator = new TaskValidator($this->repositoryMock);

        $task = new Task();
        $task->setName('Test name');
        $task->setStatus(Task::STATUS_COMPLETED);

        $this->expectException(TaskStartingException::class);
        $taskValidator->validateAbilityToStartTask($task);
    }
}