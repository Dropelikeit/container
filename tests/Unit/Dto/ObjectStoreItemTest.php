<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Dto;

use MarcelStrahl\Container\Dto\ObjectStoreItem;
use MarcelStrahl\Tests\Unit\Dto\_data\DummyAbstract;
use MarcelStrahl\Tests\Unit\Dto\_data\DummyClassWithAbstract;
use MarcelStrahl\Tests\Unit\Dto\_data\DummyClassWithInterface;
use MarcelStrahl\Tests\Unit\Dto\_data\DummyInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ObjectStoreItem::class)]
final class ObjectStoreItemTest extends TestCase
{
    public function testCanSearchById(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create($dummy::class, $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        $this->assertSame($result, $dummy);
    }

    public function testCanSearchByClass(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create('test', $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        $this->assertSame($result, $dummy);
    }

    public function testCanSearchByInterface(): void
    {
        $test = new DummyClassWithInterface();

        $item = ObjectStoreItem::create('test', $test::class, $test, [DummyInterface::class], []);

        $result = $item->searchByGivenId(DummyInterface::class);

        $this->assertSame($result, $test);
    }

    public function testCanSearchByAbstract(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyAbstract::class);

        $this->assertSame($result, $test);
    }

    public function testCanNotMatchGivenClassWithStoredObject(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyInterface::class);

        $this->assertNull($result);
    }
}
