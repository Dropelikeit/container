<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Contract\ObjectBuilder;

use MarcelStrahl\Container\Exception\ObjectBuilderFactory\UnknownBuilderTypeException;

interface ObjectBuilderFactoryInterface
{
    public const BUILDER_REFLECTION = 'reflection';
    public const BUILDER_FACTORY = 'factory';

    /**
     * @psalm-param self::BUILDER_* $builderType
     * @throws UnknownBuilderTypeException
     */
    public function factorize(string $builderType): ObjectBuilder;
}
