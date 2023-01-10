<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\ObjectBuilder\_data;

final class SimpleTestServiceWithConstructorAndOneDependency
{
    public function __construct(private SimpleTestServiceWithoutConstructor $service) {}

    public function getService(): SimpleTestServiceWithoutConstructor
    {
        return $this->service;
    }
}