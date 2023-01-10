<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto;

use InvalidArgumentException;
use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
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

        $store = ClassStore::create();
        $store->append($dummy::class, $dummy::class);

        $this->assertTrue($store->hasEntry($dummy::class));
    }

    /**
     * @test
     */
    public function canNotAppendNewEntryToStoreBecauseIdIsNotAClass(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dummy = new class() {};

        $store = ClassStore::create();
        $store->append('x', $dummy::class);
    }

    /**
     * @test
     */
    public function canNotAppendNewEntryToStoreBecauseClassArgumentIsNotAClass(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dummy = new class() {};

        $store = ClassStore::create();
        $store->append($dummy::class, 'x');
    }

    /**
     * @test
     */
    public function canSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();
        $store->append($dummy::class, $dummy::class);

        $class = $store->searchById($dummy::class);

        $this->assertSame($dummy::class, $class);
    }

    /**
     * @test
     */
    public function canNotSearchByIdSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $class = $store->searchById($dummy::class);

        $this->assertSame('', $class);
    }

    /**
     * @test
     */
    public function canSearchByValueSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();
        $store->append($dummy::class, $dummy::class);

        $class = $store->searchByValue($dummy::class);

        $this->assertSame($dummy::class, $class);
    }

    /**
     * @test
     */
    public function canNotSearchByValueSuccessful(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();

        $class = $store->searchByValue($dummy::class);

        $this->assertSame('', $class);
    }

    /**
     * @test
     */
    public function canCheckEntryExist(): void
    {
        $dummy = new class() {};

        $store = ClassStore::create();
        $store->append($dummy::class, $dummy::class);

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