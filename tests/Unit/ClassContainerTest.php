<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit;

use MarcelStrahl\Container\ClassContainer;
use MarcelStrahl\Container\Contract\ClassContainerInterface;
use MarcelStrahl\Container\Contract\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ClassContainer::class)]
#[UsesClass(className: ClassStoreInterface::class)]
#[UsesClass(className: ClassContainerInterface::class)]
#[UsesClass(className: ClassItem::class)]
#[UsesClass(className: NotFoundInContainerException::class)]
final class ClassContainerTest extends TestCase
{
    private readonly MockObject&ClassStoreInterface $classStore;

    protected function setUp(): void
    {
        $this->classStore = $this->getMockBuilder(ClassStoreInterface::class)->getMock();
    }

    public function testCanInitialize(): void
    {
        $container = ClassContainer::create($this->classStore);

        $this->assertInstanceOf(ClassContainerInterface::class, $container);
    }

    public function testCanAppendClass(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($classItem)
        ;

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
    }

    public function testCanCompile(): void
    {
        $container = ClassContainer::create($this->classStore);

        $this->assertFalse($container->isCompiled());

        $container->compile();

        $this->assertTrue($container->isCompiled());
    }

    public function testCanGetAService(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($classItem)
        ;

        $this->classStore
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($classItem)
        ;

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
        $container->get($dummy::class);
    }

    public function testCanNotGetAService(): void
    {
        $this->expectException(NotFoundInContainerException::class);

        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($classItem)
        ;

        $this->classStore
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn(null)
        ;

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
        $container->get($dummy::class);
    }

    public function testCanCheckIfEntryExist(): void
    {
        $dummy = new class() {};

        $this->classStore
            ->expects(static::once())
            ->method('hasEntry')
            ->with($dummy::class)
            ->willReturn(true)
        ;

        $container = ClassContainer::create($this->classStore);

        $this->assertTrue($container->has($dummy::class));
    }

    public function testCanCheckIfEntryNotExist(): void
    {
        $dummy = new class() {};

        $this->classStore
            ->expects(self::once())
            ->method('hasEntry')
            ->with($dummy::class)
            ->willReturn(false)
        ;

        $container = ClassContainer::create($this->classStore);

        $this->assertFalse($container->has($dummy::class));
    }
}
