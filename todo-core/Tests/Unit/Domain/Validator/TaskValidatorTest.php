<?php

namespace TodoCore\Tests\Unit\Domain\Validator;

use PHPUnit\Framework\TestCase;
use TodoCore\Domain\Entity\Task;
use TodoCore\Domain\Validator\TaskValidator;
use TodoCore\Domain\Exception\TaskNameEmptyException;
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
}