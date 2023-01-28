<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto;

use InvalidArgumentException;
use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Tests\FileLoader\data\AliasInterface;
use PHPUnit\Framework\TestCase;

final class ClassStoreTest extends TestCase
{
    /**
     * @test
     */
    public function canInitializeObject(): void
    {
        $store = ClassStore::create();

        $this->assertInstanceOf(ClassStoreInterface::class, $store);
    }

    /**
     * @test
     */
    public function canAppendNewEntryToStore(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        $this->assertTrue($store->hasEntry($dummy::class));
    }

    /**
     * @test
     */
    public function canAppendToStoreByAlias(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, ['alias' => AliasInterface::class]);

        $store = ClassStore::create();
        $store->append($classItem);

        $this->assertTrue($store->hasEntry($dummy::class));
    }

    /**
     * @test
     */
    public function canSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        $metaData = $store->searchById($dummy::class);

        $this->assertSame($classItem, $metaData);
    }

    /**
     * @test
     */
    public function canNotSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $metadata = $store->searchById($dummy::class);

        $this->assertNull($metadata);
    }

    /**
     * @test
     */
    public function canCheckEntryExist(): void
    {
        $dummy = new class() {};

        $classItem = ClassItem::create($dummy::class, []);

        $store = ClassStore::create();
        $store->append($classItem);

        $result = $store->hasEntry($dummy::class);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function canCheckEntryIsNotExist(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $result = $store->hasEntry($dummy::class);

        $this->assertFalse($result);
    }
}
