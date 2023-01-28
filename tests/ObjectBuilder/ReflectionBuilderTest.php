<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Container\ObjectBuilder;

use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectBuilder\ReflectionBuilder;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndNonClassDependency;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndOneDependency;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorButWithoutDependencies;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ReflectionBuilderTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $builder = new ReflectionBuilder();

        static::assertInstanceOf(ObjectBuilder::class, $builder);
    }

    public function testCanInitializeSimpleClassWithoutConstructor(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithoutConstructor::class);

        static::assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }

    public function testCanInitializeSimpleClassWithConstructorButWithoutDependencies(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorButWithoutDependencies::class);

        static::assertInstanceOf(SimpleTestServiceWithConstructorButWithoutDependencies::class, $object);
    }

    public function testCanInitializeSimpleClassWithConstructorAndOneDependency(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorAndOneDependency::class);

        static::assertInstanceOf(SimpleTestServiceWithConstructorAndOneDependency::class, $object);
    }

    public function testCanNotInitializeNonExistingClass(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectErrorMessage('Cannot find your class, try `composer dumpautoload` command.');

        $builder = new ReflectionBuilder();

        $builder->initialize('X\Dummy\Namespace\Class');
    }

    public function testCanNotInitializeNonClassDependency(): void
    {
        $this->expectException(CanNotCreateClassWithNoneClassDependencies::class);
        $this->expectErrorMessage('Currently, no object can be created with non-class dependencies, given type "int".');

        $builder = new ReflectionBuilder();

        $builder->initialize(SimpleTestServiceWithConstructorAndNonClassDependency::class);
    }
}
