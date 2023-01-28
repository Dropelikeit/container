<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests;

use LogicException;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectContainer;
use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ObjectContainerTest extends TestCase
{
    /**
     * @psalm-var MockObject&ObjectStoreInterface
     */
    private MockObject/*&ObjectStoreInterface*/ $store;

    /**
     * @psalm-var MockObject&ObjectBuilder
     */
    private MockObject/*&ObjectBuilder*/ $builder;

    public function setUp(): void
    {
        $this->store = $this->createMock(ObjectStoreInterface::class);
        $this->builder = $this->createMock(ObjectBuilder::class);
    }

    /**
     * @test
     */
    public function canInitialize(): void
    {
        $container = new ObjectContainer($this->store, $this->builder);

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    /**
     * @test
     */
    public function canGetObjectFromContainer(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy);

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $object);
    }

    /**
     * @test
     */
    public function canNotGetContainerWhenClassNotExist(): void
    {
        $dummy = new class() {};

        $this->expectException(NotFoundInContainerException::class);
        $this->expectErrorMessage(sprintf(
            'Can not found a entry in container with given id "%s"',
            $dummy::class,
        ));

        $exception = new LogicException();


        $this->builder
            ->expects(self::once())
            ->method('initialize')
            ->with($dummy::class)
            ->willThrowException($exception);

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null);

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $dummy);
    }

    /**
     * @test
     */
    public function initializeObjectWhenClassNotExistInObjectContainer(): void
    {
        $dummy = new class() {};

        $this->builder
            ->expects(self::once())
            ->method('initialize')
            ->with($dummy::class)
            ->willReturn($dummy);

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null);

        $this->store
            ->expects(self::once())
            ->method('append')
            ->with($dummy::class, $dummy);

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $dummy);
    }

    /**
     * @test
     */
    public function checkContainerHasObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy);

        $container = new ObjectContainer($this->store, $this->builder);

        $this->assertTrue($container->has($dummy::class));
    }

    /**
     * @test
     */
    public function checkContainerHasNotObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null);

        $container = new ObjectContainer($this->store, $this->builder);

        $this->assertFalse($container->has($dummy::class));
    }
}