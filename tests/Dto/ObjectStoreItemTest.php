<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto\Container;

use MarcelStrahl\Container\Dto\ObjectStoreItem;
use MarcelStrahl\Tests\Dto\_data\DummyAbstract;
use MarcelStrahl\Tests\Dto\_data\DummyClassWithAbstract;
use MarcelStrahl\Tests\Dto\_data\DummyClassWithInterface;
use MarcelStrahl\Tests\Dto\_data\DummyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ObjectStoreItemTest extends TestCase
{
    public function testCanSearchById(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create($dummy::class, $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        static::assertSame($result, $dummy);
    }

    public function testCanSearchByClass(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create('test', $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        static::assertSame($result, $dummy);
    }

    public function testCanSearchByInterface(): void
    {
        $test = new DummyClassWithInterface();

        $item = ObjectStoreItem::create('test', $test::class, $test, [DummyInterface::class], []);

        $result = $item->searchByGivenId(DummyInterface::class);

        static::assertSame($result, $test);
    }

    public function testCanSearchByAbstract(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyAbstract::class);

        static::assertSame($result, $test);
    }

    public function testCanNotMatchGivenClassWithStoredObject(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyInterface::class);

        static::assertNull($result);
    }
}
