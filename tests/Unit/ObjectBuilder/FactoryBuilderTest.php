<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder;

use MarcelStrahl\Container\Contract\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Container\ObjectBuilder\FactoryBuilder;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\Factory\Factory;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\Factory\FactoryWithInvoke;
use MarcelStrahl\Tests\Unit\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
#[CoversClass(className: FactoryBuilder::class)]
#[UsesClass(className: ContainerInterface::class)]
#[UsesClass(className: ObjectBuilder::class)]
#[UsesClass(className: NotFoundInContainerException::class)]
final class FactoryBuilderTest extends TestCase
{
    private readonly MockObject&ContainerInterface $container;
    private readonly MockObject&ObjectBuilder $reflectionBuilder;

    public function setUp(): void
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $this->reflectionBuilder = $this->getMockBuilder(ObjectBuilder::class)->getMock();
    }

    #[Test]
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

    #[Test]
    public function throwsExceptionWhenClassWasNotFound(): void
    {
        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage('Can not found a entry in container with given id "class"');

        (new FactoryBuilder($this->container, $this->reflectionBuilder))->initialize('class');
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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
