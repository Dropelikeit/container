<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\ObjectBuilder;

use function class_exists;
use function is_callable;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;

use MarcelStrahl\Container\Factory\FactoryInterface;
use function method_exists;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class FactoryBuilder implements ObjectBuilder
{
    public function __construct(private ContainerInterface $container, private ObjectBuilder $reflectionBuilder)
    {
    }

    public function initialize(callable|string $class): object
    {
        if (is_callable($class)) {
            return $class($this->container);
        }

        if (!class_exists($class)) {
            throw NotFoundInContainerException::create($class);
        }

        $factory = $this->reflectionBuilder->initialize($class);
        if (method_exists($factory, '__invoke')) {

            return $factory->__invoke($this->container);
        }

        if (!$factory instanceof FactoryInterface) {
            throw NotFoundInContainerException::create($class);
        }

        return $factory->factorize($this->container);
    }
}
