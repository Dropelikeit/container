<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\ObjectBuilder\_data\Factory;

use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use Psr\Container\ContainerInterface;

final class FactoryWithInvoke
{
    public function __invoke(ContainerInterface $container): SimpleTestServiceWithoutConstructor
    {
        return $container->get(SimpleTestServiceWithoutConstructor::class);
    }
}