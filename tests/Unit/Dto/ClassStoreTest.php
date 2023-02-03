<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Dto;

use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Tests\Unit\FileLoader\data\AliasInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *

 */
final class ClassStoreTest extends TestCase
{
    public function testCanInitializeObject(): void
    {
        $store = ClassStore::create();

        static::assertInstanceOf(ClassStoreInterface::class, $store);
    }

    public function testCanAppendNewEntryToStore(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        static::assertTrue($store->hasEntry($dummy::class));
    }

    public function testCanAppendToStoreByAlias(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, ['alias' => AliasInterface::class]);

        $store = ClassStore::create();
        $store->append($classItem);

        static::assertTrue($store->hasEntry($dummy::class));
    }

    public function testCanSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        $metaData = $store->searchById($dummy::class);

        static::assertSame($classItem, $metaData);
    }

    public function testCanNotSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $metadata = $store->searchById($dummy::class);

        static::assertNull($metadata);
    }

    public function testCanCheckEntryExist(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        $result = $store->hasEntry($dummy::class);

        static::assertTrue($result);
    }

    public function testCanCheckEntryIsNotExist(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $result = $store->hasEntry($dummy::class);

        static::assertFalse($result);
    }
}
