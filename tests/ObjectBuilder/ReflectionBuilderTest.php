<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Container\ObjectBuilder;

use LogicException;
use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndNonClassDependency;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndOneDependency;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithConstructorButWithoutDependencies;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectBuilder\ReflectionBuilder;
use PHPUnit\Framework\TestCase;

final class ReflectionBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function canInitialize(): void
    {
        $builder = new ReflectionBuilder();

        $this->assertInstanceOf(ObjectBuilder::class, $builder);
    }

    /**
     * @test
     */
    public function canInitializeSimpleClassWithoutConstructor(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithoutConstructor::class);

        $this->assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }

    /**
     * @test
     */
    public function canInitializeSimpleClassWithConstructorButWithoutDependencies(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorButWithoutDependencies::class);

        $this->assertInstanceOf(SimpleTestServiceWithConstructorButWithoutDependencies::class, $object);
    }

    /**
     * @test
     */
    public function canInitializeSimpleClassWithConstructorAndOneDependency(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorAndOneDependency::class);

        $this->assertInstanceOf(SimpleTestServiceWithConstructorAndOneDependency::class, $object);
    }

    /**
     * @test
     */
    public function canNotInitializeNonExistingClass(): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Cannot find your class, try `composer dumpautoload` command.');

        $builder = new ReflectionBuilder();

        $builder->initialize('X\Dummy\Namespace\Class');
    }

    /**
     * @test
     */
    public function canNotInitializeNonClassDependency(): void
    {
        $this->expectException(CanNotCreateClassWithNoneClassDependencies::class);
        $this->expectErrorMessage('Currently, no object can be created with non-class dependencies, given type "int".');

        $builder = new ReflectionBuilder();

        $builder->initialize(SimpleTestServiceWithConstructorAndNonClassDependency::class);
    }
}
