<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Delegator;

use MarcelStrahl\Container\Factory\FactoryInterface;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactoryInterface;

use function class_implements;
use function in_array;
use function method_exists;

final class ObjectDelegator implements DelegateInterface
{
    public function __construct(private ObjectBuilderFactoryInterface $builderFactory) {}

    public function delegate(string $class): object
    {
        $interfaces = class_implements($class);
        $isFactory = in_array(FactoryInterface::class, $interfaces, true);
        if (!$isFactory) {
            $isFactory = method_exists($class, '__invoke');
        }

        $type = ObjectBuilderFactoryInterface::BUILDER_REFLECTION;
        if ($isFactory) {
            $type = ObjectBuilderFactoryInterface::BUILDER_FACTORY;
        }

        $builder = $this->builderFactory->factorize($type);

        return $builder->initialize($class);
    }
}
