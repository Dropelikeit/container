<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Dto\Container;

use MarcelStrahl\Container\Dto\ObjectStoreItem;
use MarcelStrahl\Tests\Dto\_data\DummyAbstract;
use MarcelStrahl\Tests\Dto\_data\DummyClassWithAbstract;
use MarcelStrahl\Tests\Dto\_data\DummyClassWithInterface;
use MarcelStrahl\Tests\Dto\_data\DummyInterface;
use PHPUnit\Framework\TestCase;

final class ObjectStoreItemTest extends TestCase
{
    /**
     * @test
     */
    public function canSearchById(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create($dummy::class, $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        $this->assertSame($result, $dummy);
    }

    /**
     * @test
     */
    public function canSearchByClass(): void
    {
        $dummy = new class() {};

        $item = ObjectStoreItem::create('test', $dummy::class, $dummy, [], []);

        $result = $item->searchByGivenId($dummy::class);

        $this->assertSame($result, $dummy);
    }

    /**
     * @test
     */
    public function canSearchByInterface(): void
    {
        $test = new DummyClassWithInterface();

        $item = ObjectStoreItem::create('test', $test::class, $test, [DummyInterface::class], []);

        $result = $item->searchByGivenId(DummyInterface::class);

        $this->assertSame($result, $test);
    }

    /**
     * @test
     */
    public function canSearchByAbstract(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyAbstract::class);

        $this->assertSame($result, $test);
    }

    /**
     * @test
     */
    public function canNotMatchGivenClassWithStoredObject(): void
    {
        $test = new DummyClassWithAbstract();

        $item = ObjectStoreItem::create('test', $test::class, $test, [], [DummyAbstract::class]);

        $result = $item->searchByGivenId(DummyInterface::class);

        $this->assertNull($result);
    }
}