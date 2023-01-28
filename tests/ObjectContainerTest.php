<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests;

use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectContainer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @internal
 *
 * @coversNothing
 */
final class ObjectContainerTest extends TestCase
{
    /**
     * @psalm-var MockObject&ObjectStoreInterface
     */
    private MockObject/* &ObjectStoreInterface */ $store;

    /**
     * @psalm-var MockObject&ObjectBuilder
     */
    private MockObject/* &ObjectBuilder */ $builder;

    protected function setUp(): void
    {
        $this->store = $this->createMock(ObjectStoreInterface::class);
        $this->builder = $this->createMock(ObjectBuilder::class);
    }

    public function testCanInitialize(): void
    {
        $container = new ObjectContainer($this->store, $this->builder);

        static::assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testCanGetObjectFromContainer(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(static::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        static::assertInstanceOf($dummy::class, $object);
    }

    public function testCanNotGetContainerWhenClassNotExist(): void
    {
        $dummy = new class() {};

        $this->expectException(NotFoundInContainerException::class);
        $this->expectErrorMessage(sprintf(
            'Can not found a entry in container with given id "%s"',
            $dummy::class,
        ));

        $exception = new \LogicException();

        $this->builder
            ->expects(static::once())
            ->method('initialize')
            ->with($dummy::class)
            ->willThrowException($exception)
        ;

        $this->store
            ->expects(static::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        static::assertInstanceOf($dummy::class, $dummy);
    }

    public function testInitializeObjectWhenClassNotExistInObjectContainer(): void
    {
        $dummy = new class() {};

        $this->builder
            ->expects(static::once())
            ->method('initialize')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $this->store
            ->expects(static::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $this->store
            ->expects(static::once())
            ->method('append')
            ->with($dummy::class, $dummy)
        ;

        $container = new ObjectContainer($this->store, $this->builder);

        $object = $container->get($dummy::class);

        static::assertInstanceOf($dummy::class, $dummy);
    }

    public function testCheckContainerHasObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(static::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $container = new ObjectContainer($this->store, $this->builder);

        static::assertTrue($container->has($dummy::class));
    }

    public function testCheckContainerHasNotObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(static::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $container = new ObjectContainer($this->store, $this->builder);

        static::assertFalse($container->has($dummy::class));
    }
}
