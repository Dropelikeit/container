<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\ObjectBuilder;

use LogicException;
use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use ReflectionClass;
use ReflectionException;

use ReflectionParameter;
use function class_exists;

final class ReflectionBuilder implements ObjectBuilder
{
    public function initialize(string $class): object
    {
        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            throw new LogicException('Cannot find your class, try `composer dumpautoload` command.');
        }

        $parameters = $reflectionClass->getConstructor()?->getParameters();

        // Current class has no constructor and can initialize directly, or it has a constructor but no dependencies.
        if (
            $parameters === null
            || $parameters === []
        ) {
            return new $class();
        }

        // Resolve all parameters that are objects
        $arguments = $this->initializeParameters($parameters);

        // Initialize given class
        return new $class(...$arguments);
    }

    /**
     * @param array<int, ReflectionParameter> $parameters
     * @psalm-param list<ReflectionParameter> $parameters
     *
     * @return array<string, object>
     */
    private function initializeParameters(array $parameters): array
    {
        $arguments = [];
        foreach ($parameters as $parameter) {
            $type = (string) $parameter->getType();
            if (!class_exists($type)) {
                throw CanNotCreateClassWithNoneClassDependencies::create($type);
            }

            $arguments[$parameter->name] = $this->initialize($type);
        }

        return $arguments;
    }
}