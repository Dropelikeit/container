<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\ObjectBuilder;

use LogicException;
use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;

interface ObjectBuilder
{
    /**
     * @throws CanNotCreateClassWithNoneClassDependencies
     * @throws LogicException
     */
    public function initialize(string $class): object;
}
