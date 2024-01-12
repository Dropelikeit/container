<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\FileLoader;

use MarcelStrahl\Container\Contract\FileLoader\AdapterFactory;
use MarcelStrahl\Container\Contract\FileLoader\FileLoader;
use MarcelStrahl\Container\Exception\FileLoader\AdapterBuilderException;

final class AdapterBuilder implements AdapterFactory
{
    public function build(string $adapter): FileLoader
    {
        return match ($adapter) {
            self::PHP_ARRAY_CONFIG_ADAPTER, PHPArrayAdapter::class => new PHPArrayAdapter(),
            default => throw AdapterBuilderException::createByUnknownAdapterType($adapter),
        };
    }
}
