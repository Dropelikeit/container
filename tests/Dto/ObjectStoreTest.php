<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto\Container;

use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use PHPUnit\Framework\TestCase;

final class ObjectStoreTest extends TestCase
{
    /**
     * @test
     */
    public function canInitialize(): void
    {
        $store = ObjectStore::create();

        $this->assertInstanceOf(ObjectStoreInterface::class, $store);
    }

    /**
     * @test
     */
    public function canStoreObject(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass);

        $object = $store->searchById($dummyClass::class);

        $this->assertInstanceOf($dummyClass::class, $object);
    }

    /**
     * @test
     */
    public function canSearchById(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass);

        $object = $store->searchById($dummyClass::class);

        $this->assertInstanceOf($dummyClass::class, $object);
    }

    /**
     * @test
     */
    public function canNotFindAnObjectWithGivenId(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $object = $store->searchById($dummyClass::class);

        $this->assertNull($object);
    }
}