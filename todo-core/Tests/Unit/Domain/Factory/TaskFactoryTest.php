<?php

namespace TodoCore\Tests\Unit\Domain\Factory;

use PHPUnit\Framework\TestCase;
use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Factory\TaskFactory;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskNameEmptyException;
use TodoCore\Domain\Exception\TaskNameExistedException;
use TodoCore\Domain\Repository\TaskRepositoryInterface;

class TaskFactoryTest extends TestCase
{
    /**
     * @var TaskValidator
     */
    private $validatorMock;

    /**
     * @var TaskRepositoryInterface
     */
    private $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validatorMock = $this->getMockBuilder(TaskValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryMock = $this->getMockBuilder(TaskRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testPositiveCreate()
    {
        /*
         * Prepare mocks
         */
        $this->validatorMock
            ->expects($this->once())
            ->method('validateName')
            ->withAnyParameters()
            ->willReturn(true);

        $taskFactoryService = new TaskFactory($this->repositoryMock, $this->validatorMock);

        /*
         * Expected
         */
        $name = 'Make a coffee';

        /*
         * Expects
         */
        $actualTask = $taskFactoryService->create($name);
        $this->assertInstanceOf(Task::class, $actualTask);
        $this->assertEquals($name, $actualTask->getName());
        $this->assertEquals(Task::STATUS_BACKLOG, $actualTask->getStatus());
    }

    public function testNegativeNameExistedCreate()
    {
        /*
         * Prepare mocks
         */
        $this->validatorMock
            ->expects($this->once())
            ->method('validateName')
            ->willThrowException(new TaskNameExistedException());
        $taskFactoryService = new TaskFactory($this->repositoryMock, $this->validatorMock);

        /*
         * Expects
         */
        $this->expectException(TaskNameExistedException::class);
        $taskFactoryService->create('test');
    }

    public function testNegativeNameEmptyCreate()
    {
        /*
         * Prepare mocks
         */
        $this->validatorMock
            ->expects($this->once())
            ->method('validateName')
            ->willThrowException(new TaskNameEmptyException());
        $taskFactoryService = new TaskFactory($this->repositoryMock, $this->validatorMock);

        /*
         * Expects
         */
        $this->expectException(TaskNameEmptyException::class);
        $taskFactoryService->create('test');
    }
}