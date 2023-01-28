<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto;

use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStore\ClassItemInterface;
use MarcelStrahl\Tests\FileLoader\data\AliasInterface;
use MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;
use PHPUnit\Framework\TestCase;

final class ClassItemTest extends TestCase
{
    /**
     * @test
     */
    public function canInitializeObject(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, []);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame($dummy::class, $item->getClass());
        $this->assertSame($dummy::class, $item->getAlias());
        $this->assertSame($dummy::class, $item->getId());
        $this->assertFalse($item->hasFactory());
        $this->assertFalse($item->hasAlias());
    }

    /**
     * @test
     */
    public function canInitializeObjectWithFactory(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => PhpArrayLoaderClassDummyWithFactory\Factory::class,
        ]);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getAlias());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getId());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory\Factory::class, $item->getFactory());
        $this->assertTrue($item->hasFactory());
        $this->assertFalse($item->hasAlias());
    }

    /**
     * @test
     */
    public function canInitializeObjectWithId(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, ['id' => 'dummy']);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame($dummy::class, $item->getClass());
        $this->assertSame($dummy::class, $item->getAlias());
        $this->assertSame('dummy', $item->getId());
        $this->assertEmpty($item->getFactory());
        $this->assertFalse($item->hasFactory());
        $this->assertFalse($item->hasAlias());
    }

    /**
     * @test
     */
    public function canInitializeObjectWithAlias(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, ['alias' => AliasInterface::class]);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame($dummy::class, $item->getClass());
        $this->assertSame(AliasInterface::class, $item->getAlias());
        $this->assertSame($dummy::class, $item->getId());
        $this->assertEmpty($item->getFactory());
        $this->assertFalse($item->hasFactory());
        $this->assertTrue($item->hasAlias());
    }

    public function canInitializeObjectWithFullConfiguration(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => PhpArrayLoaderClassDummyWithFactory\Factory::class,
            'id' => 'dummy',
            'alias' => AliasInterface::class,
        ]);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        $this->assertSame(AliasInterface::class, $item->getAlias());
        $this->assertSame('dummy', $item->getId());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory\Factory::class, $item->getFactory());
        $this->assertTrue($item->hasFactory());
        $this->assertTrue($item->hasAlias());
    }
}