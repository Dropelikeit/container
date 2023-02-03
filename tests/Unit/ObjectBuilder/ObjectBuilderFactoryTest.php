<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\ObjectBuilder;

use InvalidArgumentException;
use MarcelStrahl\Container\Exception\ObjectBuilderFactory\UnknownBuilderTypeException;
use MarcelStrahl\Container\ObjectBuilder\FactoryBuilder;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactory;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactoryInterface;
use MarcelStrahl\Container\ObjectBuilder\ReflectionBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ObjectBuilderFactoryTest extends TestCase
{
    /**
     * @psalm-var MockObject&ContainerInterface
     */
    private MockObject $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @test
     */
    public function canInitializeFactory(): void
    {
        $factory = new ObjectBuilderFactory();

        $this->assertInstanceOf(ObjectBuilderFactoryInterface::class, $factory);
    }

    /**
     * @test
     */
    public function canGetReflectionBuilder(): void
    {
        $factory = new ObjectBuilderFactory();
        $factory->setContainer($this->container);

        $builder = $factory->factorize('reflection');

        $this->assertInstanceOf(ReflectionBuilder::class, $builder);
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionBecauseContainerIsNotSet(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('It is important that you set the container before calling `factorize`.');

        $factory = new ObjectBuilderFactory();

        $builder = $factory->factorize('reflection');

        $this->assertEquals(FactoryBuilder::class, $builder);
    }

    /**
     * @test
     */
    public function canGetFactoryBuilder(): void
    {
        $factory = new ObjectBuilderFactory();
        $factory->setContainer($this->container);

        $builder = $factory->factorize('factory');

        $this->assertInstanceOf(FactoryBuilder::class, $builder);
    }

    /**
     * @test
     */
    public function throwsExceptionBecauseUnknownBuilderTypeIsGiven(): void
    {
        $this->expectException(UnknownBuilderTypeException::class);
        $this->expectExceptionMessage('Unknown builder type detected. Given: "dummy_type"');

        $factory = new ObjectBuilderFactory();
        $factory->setContainer($this->container);

        $builder = $factory->factorize('dummy_type');

        $this->assertInstanceOf(FactoryBuilder::class, $builder);
    }
}
