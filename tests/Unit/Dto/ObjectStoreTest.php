<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Dto;

use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *

 */
final class ObjectStoreTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $store = ObjectStore::create();

        static::assertInstanceOf(ObjectStoreInterface::class, $store);
    }

    public function testCanStoreObject(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass());

        $object = $store->searchById($dummyClass::class);

        static::assertInstanceOf($dummyClass::class, $object);
    }

    public function testCanSearchById(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass());

        $object = $store->searchById($dummyClass::class);

        static::assertInstanceOf($dummyClass::class, $object);
    }

    public function testCanNotFindAnObjectWithGivenId(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $object = $store->searchById($dummyClass::class);

        static::assertNull($object);
    }
}
