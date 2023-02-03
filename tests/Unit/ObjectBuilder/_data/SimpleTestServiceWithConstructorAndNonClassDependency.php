<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder\_data;

final class SimpleTestServiceWithConstructorAndNonClassDependency
{
    public function __construct(private int $count)
    {
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
