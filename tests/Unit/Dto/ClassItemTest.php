<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Dto;

use MarcelStrahl\Container\Contract\Dto\ClassStore\ClassItemInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Tests\Unit\FileLoader\data\AliasInterface;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ClassItem::class)]
final class ClassItemTest extends TestCase
{
    #[Test]
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

    #[Test]
    public function canInitializeObjectWithFactory(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => Factory::class,
        ]);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getAlias());
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getId());
        $this->assertSame(Factory::class, $item->getFactory());
        $this->assertTrue($item->hasFactory());
        $this->assertFalse($item->hasAlias());
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function canInitializeObjectWithFullConfiguration(): void
    {
        $item = ClassItem::create(PhpArrayLoaderClassDummyWithFactory::class, [
            'factory' => Factory::class,
            'id' => 'dummy',
            'alias' => AliasInterface::class,
        ]);

        $this->assertInstanceOf(ClassItemInterface::class, $item);
        $this->assertSame(PhpArrayLoaderClassDummyWithFactory::class, $item->getClass());
        $this->assertSame(AliasInterface::class, $item->getAlias());
        $this->assertSame('dummy', $item->getId());
        $this->assertSame(Factory::class, $item->getFactory());
        $this->assertTrue($item->hasFactory());
        $this->assertTrue($item->hasAlias());
    }
}
