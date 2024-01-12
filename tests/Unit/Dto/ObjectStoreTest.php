<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Dto;

use MarcelStrahl\Container\Contract\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Container\Dto\ObjectStoreItem;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ObjectStore::class)]
#[UsesClass(className: ObjectStoreInterface::class)]
#[UsesClass(className: ObjectStoreItem::class)]
final class ObjectStoreTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $store = ObjectStore::create();

        $this->assertInstanceOf(ObjectStoreInterface::class, $store);
    }

    public function testCanStoreObject(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass());

        $object = $store->searchById($dummyClass::class);

        $this->assertInstanceOf($dummyClass::class, $object);
    }

    public function testCanSearchById(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $store->append($dummyClass::class, new $dummyClass());

        $object = $store->searchById($dummyClass::class);

        $this->assertInstanceOf($dummyClass::class, $object);
    }

    public function testCanNotFindAnObjectWithGivenId(): void
    {
        $dummyClass = new class() {};

        $store = ObjectStore::create();

        $object = $store->searchById($dummyClass::class);

        $this->assertNull($object);
    }
}
