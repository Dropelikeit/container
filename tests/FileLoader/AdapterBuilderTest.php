<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\FileLoader;

use MarcelStrahl\Container\Exception\FileLoader\AdapterBuilderException;
use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\AdapterFactory;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class AdapterBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function canInitializeBuilder(): void
    {
        $builder = new AdapterBuilder();

        $this->assertInstanceOf(AdapterFactory::class, $builder);
    }

    /**
     * @test
     */
    public function canBuildPhpArrayConfigAdapterWithGivenClassName(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(PHPArrayAdapter::class);

        $this->assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    /**
     * @test
     */
    public function canBuildPhpArrayConfigAdapterWithGivenId(): void
    {
        $builder = new AdapterBuilder();

        $adapter = $builder->build(AdapterFactory::PHP_ARRAY_CONFIG_ADAPTER);

        $this->assertInstanceOf(PHPArrayAdapter::class, $adapter);
    }

    /**
     * @test
     */
    public function throwExceptionIfGivenArgumentIsUnknown(): void
    {
        $this->expectException(AdapterBuilderException::class);
        $this->expectExceptionMessage('Given unknown adapter type "dummy"');

        (new AdapterBuilder())->build('dummy');
    }
}
