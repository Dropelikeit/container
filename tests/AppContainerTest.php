<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests;

use MarcelStrahl\Container\AppContainer;
use MarcelStrahl\Container\ClassContainerInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Exception\CannotRetrieveException;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Tests\ObjectBuilder\_data\Factory\FactoryWithInvoke;
use MarcelStrahl\Tests\ObjectBuilder\_data\SimpleTestServiceWithoutConstructor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 *
 * @internal
 */
final class AppContainerTest extends TestCase
{
    /**
     * @psalm-var ClassContainerInterface&MockObject
     */
    private MockObject $classContainer;

    /**
     * @psalm-var ContainerInterface&MockObject
     */
    private MockObject $objectContainer;

    protected function setUp(): void
    {
        $this->classContainer = $this->createMock(ClassContainerInterface::class);
        $this->objectContainer = $this->createMock(ContainerInterface::class);
    }

    public function testCanInitializeMainContainer(): void
    {
        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        static::assertInstanceOf(AppContainer::class, $container);
    }

    public function testThrowAnExceptionBecauseTheContainerHasNoServiceWithGivenId(): void
    {
        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage('Can not found a entry in container with given id "test"');

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $container->get('test');
    }

    public function testThrowAnExceptionBecauseTheContainerCannotRetrieveTheRequestedService(): void
    {
        $dummy = new class() {
        };

        $this->expectException(CannotRetrieveException::class);
        $this->expectExceptionMessage(sprintf('Cannot initialize object "%s"', $dummy::class));

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with($dummy::class)
            ->willReturn(true);

        $item = ClassItem::create($dummy::class, []);

        $this->classContainer
            ->expects(static::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($item);

        $this->objectContainer
            ->expects(static::once())
            ->method('get')
            ->with($dummy::class)
            ->willThrowException(NotFoundInContainerException::create($dummy::class));

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $object = $container->get($dummy::class);
    }

    public function testCanGetAService(): void
    {
        $dummy = new class() {
        };

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with($dummy::class)
            ->willReturn(true);

        $item = ClassItem::create($dummy::class, []);

        $this->classContainer
            ->expects(static::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($item);

        $this->objectContainer
            ->expects(static::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($dummy);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $object = $container->get($dummy::class);

        static::assertSame($dummy, $object);
    }

    public function testGivenIdDoNotExist(): void
    {
        $this->objectContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        static::assertFalse($container->has('test'));
    }

    public function testGivenIdExistInObjectContainer(): void
    {
        $this->objectContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(true);

        $this->classContainer
            ->expects(static::never())
            ->method('has')
            ->with('test');

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        static::assertTrue($container->has('test'));
    }

    public function testGivenIdExistInClassContainer(): void
    {
        $this->objectContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with('test')
            ->willReturn(true);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        static::assertTrue($container->has('test'));
    }

    /**
     * @test
     */
    public function canGetServiceByFactory(): void
    {
        $object = new SimpleTestServiceWithoutConstructor();

        $item = ClassItem::create(SimpleTestServiceWithoutConstructor::class, [
            'factory' => FactoryWithInvoke::class,
        ]);

        $this->objectContainer
            ->expects(static::once())
            ->method('get')
            ->with(FactoryWithInvoke::class)
            ->willReturn($object);

        $this->classContainer
            ->expects(self::once())
            ->method('get')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn($item);

        $this->classContainer
            ->expects(static::once())
            ->method('has')
            ->with(SimpleTestServiceWithoutConstructor::class)
            ->willReturn(true);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);
        $object = $container->get(SimpleTestServiceWithoutConstructor::class);

        $this->assertInstanceOf(SimpleTestServiceWithoutConstructor::class, $object);
    }
}
