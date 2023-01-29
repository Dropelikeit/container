<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests;

use MarcelStrahl\Container\ClassContainer;
use MarcelStrahl\Container\ClassContainerInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 */
final class ClassContainerTest extends TestCase
{
    /**
     * @psalm-var MockObject&ClassStoreInterface
     */
    private MockObject/* &ClassStoreInterface */ $classStore;

    protected function setUp(): void
    {
        $this->classStore = $this->createMock(ClassStoreInterface::class);
    }

    public function testCanInitialize(): void
    {
        $container = ClassContainer::create($this->classStore);

        static::assertInstanceOf(ClassContainerInterface::class, $container);
    }

    public function testCanAppendClass(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(static::once())
            ->method('append')
            ->with($classItem)
        ;

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
    }

    public function testCanCompile(): void
    {
        $container = ClassContainer::create($this->classStore);

        static::assertFalse($container->isCompiled());

        $container->compile();

        static::assertTrue($container->isCompiled());
    }

    public function testCanGetAService(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(static::once())
            ->method('append')
            ->with($classItem)
        ;

        $this->classStore
            ->expects(static::once())
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
            ->expects(static::once())
            ->method('append')
            ->with($classItem)
        ;

        $this->classStore
            ->expects(static::once())
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

        static::assertTrue($container->has($dummy::class));
    }

    public function testCanCheckIfEntryNotExist(): void
    {
        $dummy = new class() {};

        $this->classStore
            ->expects(static::once())
            ->method('hasEntry')
            ->with($dummy::class)
            ->willReturn(false)
        ;

        $container = ClassContainer::create($this->classStore);

        static::assertFalse($container->has($dummy::class));
    }
}
