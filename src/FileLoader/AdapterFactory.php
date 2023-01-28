<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\FileLoader;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
interface AdapterFactory
{
    public const PHP_ARRAY_CONFIG_ADAPTER = 'php_array';

    /**
     * @psalm-param self::PHP_ARRAY_CONFIG_ADAPTER|class-string $adapter
     */
    public function build(string $adapter): FileLoader;
}
