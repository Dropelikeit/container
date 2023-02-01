<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Delegator;

use MarcelStrahl\Container\Delegator\ObjectDelegator;
use MarcelStrahl\Container\Factory\FactoryInterface;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactoryInterface;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class ObjectDelegatorTest extends TestCase
{
    /**
     * @psalm-var MockObject&ObjectBuilderFactoryInterface
     */
    private MockObject $builderFactory;

    /**
     * @psalm-var MockObject&ObjectBuilder
     */
    private MockObject $builder;

    /**
     * @psalm-var MockObject&ContainerInterface
     */
    private MockObject $container;

    public function setUp(): void
    {
        $this->builderFactory = $this->createMock(ObjectBuilderFactoryInterface::class);
        $this->builder = $this->createMock(ObjectBuilder::class);
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @test
     */
    public function canDetectFactoryByInvokeMethod(): void
    {
        $dummyFactory = new class {
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

    /**
     * @test
     */
    public function canDelegateFactoryWithFactoryInterface(): void
    {
        $dummyFactory = new class implements FactoryInterface {
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

    /**
     * @test
     */
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
