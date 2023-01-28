<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests;

use MarcelStrahl\Container\AppContainer;
use MarcelStrahl\Container\ClassContainerInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Exception\CannotRetrieveException;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
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

    public function setUp(): void
    {
        $this->classContainer = $this->createMock(ClassContainerInterface::class);
        $this->objectContainer = $this->createMock(ContainerInterface::class);
    }

    /**
     * @test
     */
    public function canInitializeMainContainer(): void
    {
        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $this->assertInstanceOf(AppContainer::class, $container);
    }

    /**
     * @test
     */
    public function throwAnExceptionBecauseTheContainerHasNoServiceWithGivenId(): void
    {
        $this->expectException(NotFoundInContainerException::class);
        $this->expectExceptionMessage('Can not found a entry in container with given id "test"');

        $this->classContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $container->get('test');
    }

    /**
     * @test
     */
    public function throwAnExceptionBecauseTheContainerCannotRetrieveTheRequestedService(): void
    {
        $dummy = new class () {};

        $this->expectException(CannotRetrieveException::class);
        $this->expectExceptionMessage(sprintf('Cannot initialize object "%s"', $dummy::class));

        $this->classContainer
            ->expects(self::once())
            ->method('has')
            ->with($dummy::class)
            ->willReturn(true);

        $item = ClassItem::create($dummy::class, []);

        $this->classContainer
            ->expects(self::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($item);

        $this->objectContainer
            ->expects(self::once())
            ->method('get')
            ->with($dummy::class)
            ->willThrowException(NotFoundInContainerException::create($dummy::class));

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $object = $container->get($dummy::class);
    }

    /**
     * @test
     */
    public function canGetAService(): void
    {
        $dummy = new class () {};

        $this->classContainer
            ->expects(self::once())
            ->method('has')
            ->with($dummy::class)
            ->willReturn(true);

        $item = ClassItem::create($dummy::class, []);

        $this->classContainer
            ->expects(self::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($item);

        $this->objectContainer
            ->expects(self::once())
            ->method('get')
            ->with($dummy::class)
            ->willReturn($dummy);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $object = $container->get($dummy::class);

        $this->assertEquals($dummy, $object);
    }

    /**
     * @test
     */
    public function givenIdDoNotExist(): void
    {
        $this->objectContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $this->classContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $this->assertFalse($container->has('test'));
    }

    /**
     * @test
     */
    public function givenIdExistInObjectContainer(): void
    {
        $this->objectContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(true);

        $this->classContainer
            ->expects(self::never())
            ->method('has')
            ->with('test');

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $this->assertTrue($container->has('test'));
    }

    /**
     * @test
     */
    public function givenIdExistInClassContainer(): void
    {
        $this->objectContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(false);

        $this->classContainer
            ->expects(self::once())
            ->method('has')
            ->with('test')
            ->willReturn(true);

        $container = AppContainer::initialize($this->classContainer, $this->objectContainer);

        $this->assertTrue($container->has('test'));
    }
}
