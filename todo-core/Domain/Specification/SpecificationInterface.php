<?php

namespace TodoCore\Domain\Specification;

/**
 * Interface SpecificationInterface
 * @package TodoCore\Domain\Specification
 */
interface SpecificationInterface
{
    /**
     * Retutn the weather if condition of specification is satisfied
     *
     * @return bool
     */
    public function isSatisfied(): bool;
}