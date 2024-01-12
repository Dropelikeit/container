<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder;

use LogicException;
use MarcelStrahl\Container\Contract\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use MarcelStrahl\Container\ObjectBuilder\ReflectionBuilder;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndNonClassDependency;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithConstructorAndOneDependency;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithConstructorButWithoutDependencies;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ReflectionBuilder::class)]
#[UsesClass(className: ObjectBuilder::class)]
#[UsesClass(className: CanNotCreateClassWithNoneClassDependencies::class)]
final class ReflectionBuilderTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $builder = new ReflectionBuilder();

        $this->assertInstanceOf(ObjectBuilder::class, $builder);
    }

    public function testCanInitializeSimpleClassWithoutConstructor(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithoutConstructor::class);

        $this->assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }

    public function testCanInitializeSimpleClassWithConstructorButWithoutDependencies(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorButWithoutDependencies::class);

        $this->assertInstanceOf(SimpleTestServiceWithConstructorButWithoutDependencies::class, $object);
    }

    public function testCanInitializeSimpleClassWithConstructorAndOneDependency(): void
    {
        $builder = new ReflectionBuilder();

        $object = $builder->initialize(SimpleTestServiceWithConstructorAndOneDependency::class);

        $this->assertInstanceOf(SimpleTestServiceWithConstructorAndOneDependency::class, $object);
    }

    public function testCanNotInitializeNonExistingClass(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot find your class, try `composer dumpautoload` command.');

        $builder = new ReflectionBuilder();

        $builder->initialize('X\Dummy\Namespace\Class');
    }

    public function testCanNotInitializeNonClassDependency(): void
    {
        $this->expectException(CanNotCreateClassWithNoneClassDependencies::class);
        $this->expectExceptionMessage('Currently, no object can be created with non-class dependencies, given type "int".');

        $builder = new ReflectionBuilder();

        $builder->initialize(SimpleTestServiceWithConstructorAndNonClassDependency::class);
    }

    #[Test]
    public function throwLogicExceptionWhenReflectionBuilderIsPassedCallables(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('callables are not yet supported.');

        $closure = static function (): string {
            return 'dummy';
        };

        $builder = new ReflectionBuilder();

        $builder->initialize($closure);
    }
}
