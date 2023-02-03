<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder;

use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Container\ObjectBuilder\FactoryBuilder;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\Factory\Factory;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\Factory\FactoryWithInvoke;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class FactoryBuilderTest extends TestCase
{
    /**
     * @psalm-var MockObject&ContainerInterface
     */
    private MockObject $container;

    /**
     * @psalm-var MockObject&ObjectBuilder
     */
    private MockObject $reflectionBuilder;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->reflectionBuilder = $this->createMock(ObjectBuilder::class);
    }

    /**
     * @test
     */
    public function canHandleCallables(): void
    {
        $object = new SimpleTestServiceWithoutConstructor();

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn($object);

        $builder = new FactoryBuilder($this->container, $this->reflectionBuilder);

        $requestedObject = $builder->initialize(static function (ContainerInterface $container): SimpleTestServiceWithoutConstructor {
            return $container->get(SimpleTestServiceWithoutConstructor::class);
        });

        $this->assertEquals($object, $requestedObject);
    }

    /**
     * @test
     */
    public function throwsExceptionWhenClassWasNotFound(): void
    {
        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage('Can not found a entry in container with given id "class"');

        (new FactoryBuilder($this->container, $this->reflectionBuilder))->initialize('class');
    }

    /**
     * @test
     */
    public function factoryIsInvokable(): void
    {
        $factory = new FactoryWithInvoke();
        $object = new SimpleTestServiceWithoutConstructor();

        $this->reflectionBuilder
            ->expects(self::once())
            ->method('initialize')
            ->with(FactoryWithInvoke::class)
            ->willReturn($factory);

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn($object);

        $builder = new FactoryBuilder($this->container, $this->reflectionBuilder);

        $requestedObject = $builder->initialize(FactoryWithInvoke::class);

        $this->assertEquals($object, $requestedObject);
    }

    /**
     * @test
     */
    public function givenFactoryHasFactoryInterfaceImplemented(): void
    {
        $factory = new Factory();
        $object = new SimpleTestServiceWithoutConstructor();

        $this->reflectionBuilder
            ->expects(self::once())
            ->method('initialize')
            ->with(Factory::class)
            ->willReturn($factory);

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn($object);

        $builder = new FactoryBuilder($this->container, $this->reflectionBuilder);

        $requestedObject = $builder->initialize(Factory::class);

        $this->assertEquals($object, $requestedObject);
    }

    /**
     * @test
     */
    public function throwsFactoryNotFoundExceptionWhenClassHasNoFactoryRequirements(): void
    {
        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage(
            'Can not found a entry in container with given id "MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor"'
        );

        $factory = new SimpleTestServiceWithoutConstructor();

        $this->reflectionBuilder
            ->expects(self::once())
            ->method('initialize')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn($factory);

        (new FactoryBuilder($this->container, $this->reflectionBuilder))
            ->initialize(SimpleTestServiceWithoutConstructor::class);
    }
}
