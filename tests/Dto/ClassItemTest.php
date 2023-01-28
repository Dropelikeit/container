<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto;

use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStore\ClassItemInterface;
use MarcelStrahl\Tests\FileLoader\data\AliasInterface;
use MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ClassItemTest extends TestCase
{
    public function testCanInitializeObject(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, []);

        static::assertInstanceOf(ClassItemInterface::class, $item);
        static::assertSame($dummy::class, $item->getClass());
        static::assertSame($dummy::class, $item->getAlias());
        static::assertSame($dummy::class, $item->getId());
        static::assertFalse($item->hasFactory());
        static::assertFalse($item->hasAlias());
    }

    public function testCanInitializeObjectWithFactory(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => PhpArrayLoaderClassDummyWithFactory\Factory::class,
        ]);

        static::assertInstanceOf(ClassItemInterface::class, $item);
        static::assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        static::assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getAlias());
        static::assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getId());
        static::assertSame(PhpArrayLoaderClassDummyWithFactory\Factory::class, $item->getFactory());
        static::assertTrue($item->hasFactory());
        static::assertFalse($item->hasAlias());
    }

    public function testCanInitializeObjectWithId(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, ['id' => 'dummy']);

        static::assertInstanceOf(ClassItemInterface::class, $item);
        static::assertSame($dummy::class, $item->getClass());
        static::assertSame($dummy::class, $item->getAlias());
        static::assertSame('dummy', $item->getId());
        static::assertEmpty($item->getFactory());
        static::assertFalse($item->hasFactory());
        static::assertFalse($item->hasAlias());
    }

    public function testCanInitializeObjectWithAlias(): void
    {
        $dummy = new class() {};

        $item = ClassItem::create($dummy::class, ['alias' => AliasInterface::class]);

        static::assertInstanceOf(ClassItemInterface::class, $item);
        static::assertSame($dummy::class, $item->getClass());
        static::assertSame(AliasInterface::class, $item->getAlias());
        static::assertSame($dummy::class, $item->getId());
        static::assertEmpty($item->getFactory());
        static::assertFalse($item->hasFactory());
        static::assertTrue($item->hasAlias());
    }

    public function canInitializeObjectWithFullConfiguration(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => PhpArrayLoaderClassDummyWithFactory\Factory::class,
            'id' => 'dummy',
            'alias' => AliasInterface::class,
        ]);

        static::assertInstanceOf(ClassItemInterface::class, $item);
        static::assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        static::assertSame(AliasInterface::class, $item->getAlias());
        static::assertSame('dummy', $item->getId());
        static::assertSame(PhpArrayLoaderClassDummyWithFactory\Factory::class, $item->getFactory());
        static::assertTrue($item->hasFactory());
        static::assertTrue($item->hasAlias());
    }
}
