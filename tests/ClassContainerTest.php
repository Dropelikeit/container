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

final class ClassContainerTest extends TestCase
{
    /**
     * @psalm-var MockObject&ClassStoreInterface
     */
    private MockObject/*&ClassStoreInterface*/ $classStore;

    public function setUp(): void
    {
        $this->classStore = $this->createMock(ClassStoreInterface::class);
    }

    /**
     * @test
     */
    public function canInitialize(): void
    {
        $container = ClassContainer::create($this->classStore);

        $this->assertInstanceOf(ClassContainerInterface::class, $container);
    }

    /**
     * @test
     */
    public function canAppendClass(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($classItem);

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
    }

    /**
     * @test
     */
    public function canCompile(): void
    {
        $container = ClassContainer::create($this->classStore);

        $this->assertFalse($container->isCompiled());

        $container->compile();

        $this->assertTrue($container->isCompiled());
    }

    /**
     * @test
     */
    public function canGetAService(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($dummy::class, $dummy::class);

        $this->classStore
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn($classItem);

        $container = ClassContainer::create($this->classStore);
        $container->append($classItem);
        $container->get($dummy::class);
    }

    /**
     * @test
     */
    public function canNotGetAService(): void
    {
        $this->expectException(NotFoundInContainerException::class);

        $dummy = new class() {};

        $this->classStore
            ->expects(self::once())
            ->method('append')
            ->with($dummy::class, $dummy::class);

        $this->classStore
            ->expects(self::once())
            ->method('searchById')
            ->with($dummy::class)
            ->willReturn('');

        $container = ClassContainer::create($this->classStore);
        $container->append($dummy::class, $dummy::class);
        $container->get($dummy::class);
    }

    /**
     * @test
     */
    public function canCheckIfEntryExist(): void
    {
        $dummy = new class() {};

        $this->classStore
            ->expects(self::once())
            ->method('hasEntry')
            ->with($dummy::class)
            ->willReturn(true);

        $container = ClassContainer::create($this->classStore);

        $this->assertTrue($container->has($dummy::class));
    }

    /**
     * @test
     */
    public function canCheckIfEntryNotExist(): void
    {
        $dummy = new class() {};

        $this->classStore
            ->expects(self::once())
            ->method('hasEntry')
            ->with($dummy::class)
            ->willReturn(false);

        $container = ClassContainer::create($this->classStore);

        $this->assertFalse($container->has($dummy::class));
    }
}