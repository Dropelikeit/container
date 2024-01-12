<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\FileLoader;

use MarcelStrahl\Container\Contract\FileLoader\AdapterFactory;
use MarcelStrahl\Container\Exception\FileLoader\AdapterBuilderException;
use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: AdapterBuilder::class)]
#[UsesClass(className: AdapterFactory::class)]
#[UsesClass(className: AdapterBuilderException::class)]
final class AdapterBuilderTest extends TestCase
{
    #[Test]
    public function canInitializeBuilder(): void
    {
        $builder = new AdapterBuilder();

        $this->assertInstanceOf(AdapterFactory::class, $builder);
    }

    #[Test]
    public function canBuildPhpArrayConfigAdapterWithGivenClassName(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(PHPArrayAdapter::class);

        $this->assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    #[Test]
    public function canBuildPhpArrayConfigAdapterWithGivenId(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(AdapterFactory::PHP_ARRAY_CONFIG_ADAPTER);

        $this->assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    #[Test]
    public function throwExceptionIfGivenArgumentIsUnknown(): void
    {
        $this->expectException(AdapterBuilderException::class);
        $this->expectExceptionMessage('Given unknown adapter type "dummy"');

        (new AdapterBuilder())->build('dummy');
    }
}
