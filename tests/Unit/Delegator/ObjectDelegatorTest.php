<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Delegator;

use MarcelStrahl\Container\Contract\Factory\FactoryInterface;
use MarcelStrahl\Container\Contract\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\Contract\ObjectBuilder\ObjectBuilderFactoryInterface;
use MarcelStrahl\Container\Delegator\ObjectDelegator;
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
#[CoversClass(className: ObjectDelegator::class)]
#[UsesClass(className: ObjectBuilderFactoryInterface::class)]
#[UsesClass(className: ObjectBuilder::class)]
final class ObjectDelegatorTest extends TestCase
{
    private readonly MockObject&ObjectBuilderFactoryInterface $builderFactory;
    private readonly MockObject&ObjectBuilder $builder;
    private readonly MockObject&ContainerInterface $container;

    public function setUp(): void
    {
        $this->builderFactory = $this->getMockBuilder(ObjectBuilderFactoryInterface::class)->getMock();
        $this->builder = $this->getMockBuilder(ObjectBuilder::class)->getMock();
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
    }

    #[Test]
    public function canDetectFactoryByInvokeMethod(): void
    {
        $dummyFactory = new class() {
            public function __invoke(ContainerInterface $container): object
            {
                return new SimpleTestServiceWithoutConstructor();
            }

        };

        $this->builder
            ->expects(self::once())
            ->method('initialize')
            ->with($dummyFactory::class)
            ->willReturn(new SimpleTestServiceWithoutConstructor());

        $this->builderFactory
            ->expects(self::once())
            ->method('factorize')
            ->with('factory')
            ->willReturn($this->builder);

        $delegator = new ObjectDelegator($this->builderFactory);
        $object = $delegator->delegate($dummyFactory::class);

        $this->assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }

    #[Test]
    public function canDelegateFactoryWithFactoryInterface(): void
    {
        $dummyFactory = new class() implements FactoryInterface {
            public function factorize(ContainerInterface $container): object
            {
                return new SimpleTestServiceWithoutConstructor();
            }
        };

        $this->builder
            ->expects(self::once())
            ->method('initialize')
            ->with($dummyFactory::class)
            ->willReturn(new SimpleTestServiceWithoutConstructor());

        $this->builderFactory
            ->expects(self::once())
            ->method('factorize')
            ->with('factory')
            ->willReturn($this->builder);

        $delegator = new ObjectDelegator($this->builderFactory);
        $object = $delegator->delegate($dummyFactory::class);

        $this->assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }

    #[Test]
    public function canDelegateWithoutFactory(): void
    {
        $dummy = new class() {};

        $this->builder
            ->expects(self::once())
            ->method('initialize')
            ->with($dummy::class)
            ->willReturn($dummy);

        $this->builderFactory
            ->expects(self::once())
            ->method('factorize')
            ->with('reflection')
            ->willReturn($this->builder);

        $delegator = new ObjectDelegator($this->builderFactory);
        $object = $delegator->delegate($dummy::class);

        $this->assertSame($dummy, $object);
    }
}
