<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit;

use MarcelStrahl\Container\Contract\Delegator\DelegateInterface;
use MarcelStrahl\Container\Contract\Dto\ObjectStoreInterface;

use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Container\ObjectContainer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

#[CoversClass(className: ObjectContainer::class)]
#[UsesClass(className: ObjectStoreInterface::class)]
#[UsesClass(className: DelegateInterface::class)]
#[UsesClass(className: DelegateInterface::class)]
#[UsesClass(className: NotFoundInContainerException::class)]
final class ObjectContainerTest extends TestCase
{
    private readonly MockObject&ObjectStoreInterface $store;
    private readonly MockObject&DelegateInterface $delegator;

    protected function setUp(): void
    {
        $this->store = $this->getMockBuilder(ObjectStoreInterface::class)->getMock();
        $this->delegator = $this->getMockBuilder(DelegateInterface::class)->getMock();
    }

    public function testCanInitialize(): void
    {
        $container = new ObjectContainer($this->store, $this->delegator);

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testCanGetObjectFromContainer(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $container = new ObjectContainer($this->store, $this->delegator);

        $object = $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $object);
    }

    public function testCanNotGetContainerWhenClassNotExist(): void
    {
        $dummy = new class() {};

        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage(sprintf(
            'Can not found a entry in container with given id "%s"',
            $dummy::class,
        ));

        $exception = new \LogicException();

        $this->delegator
            ->expects(self::once())
            ->method('delegate')
            ->with($dummy::class)
            ->willThrowException($exception)
        ;

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $container = new ObjectContainer($this->store, $this->delegator);

        $object = $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $dummy);
    }

    public function testInitializeObjectWhenClassNotExistInObjectContainer(): void
    {
        $dummy = new class() {};

        $this->delegator
            ->expects(self::once())
            ->method('delegate')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $this->store
            ->expects(self::once())
            ->method('append')
            ->with($dummy::class, $dummy)
        ;

        $container = new ObjectContainer($this->store, $this->delegator);

        $container->get($dummy::class);

        $this->assertInstanceOf($dummy::class, $dummy);
    }

    public function testCheckContainerHasObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($dummy)
        ;

        $container = new ObjectContainer($this->store, $this->delegator);

        $this->assertTrue($container->has($dummy::class));
    }

    public function testCheckContainerHasNotObject(): void
    {
        $dummy = new class() {};

        $this->store
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $container = new ObjectContainer($this->store, $this->delegator);

        $this->assertFalse($container->has($dummy::class));
    }
}
