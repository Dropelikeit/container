<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\FileLoader;

use MarcelStrahl\Container\Exception\FileLoader\AdapterBuilderException;
use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\AdapterFactory;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 *
 * @internal
 *
 */
final class AdapterBuilderTest extends TestCase
{
    public function testCanInitializeBuilder(): void
    {
        $builder = new AdapterBuilder();

        static::assertInstanceOf(AdapterFactory::class, $builder);
    }

    public function testCanBuildPhpArrayConfigAdapterWithGivenClassName(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(PHPArrayAdapter::class);

        static::assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    public function testCanBuildPhpArrayConfigAdapterWithGivenId(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(AdapterFactory::PHP_ARRAY_CONFIG_ADAPTER);

        static::assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    public function testThrowExceptionIfGivenArgumentIsUnknown(): void
    {
        $this->expectException(AdapterBuilderException::class);
        $this->expectExceptionMessage('Given unknown adapter type "dummy"');

        (new AdapterBuilder())->build('dummy');
    }
}
