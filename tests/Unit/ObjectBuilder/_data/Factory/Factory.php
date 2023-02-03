<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder\_data\Factory;

use MarcelStrahl\Container\Factory\FactoryInterface;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use Psr\Container\ContainerInterface;

final class Factory implements FactoryInterface
{
    public function factorize(ContainerInterface $container): object
    {
        return $container->get(SimpleTestServiceWithoutConstructor::class);
    }
}
