<?php

namespace TodoCore\Domain\Specification;

/**
 * Class TaskNameIsNotEmptySpec
 *
 * Specification describes that Task's name should not be empty
 *
 * @package TodoCore\Domain\Specification
 */
class TaskNameIsNotEmptySpec implements SpecificationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * TaskNameIsNotEmptySpec constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function isSatisfied(): bool
    {
        return $this->name !== '';
    }
}
