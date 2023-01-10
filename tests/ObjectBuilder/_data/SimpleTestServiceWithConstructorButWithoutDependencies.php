<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\ObjectBuilder\_data;

final class SimpleTestServiceWithConstructorButWithoutDependencies
{
    public function __construct() {}
}