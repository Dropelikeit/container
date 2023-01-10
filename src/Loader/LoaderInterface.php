<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Loader;

interface LoaderInterface
{
    public function load(mixed $config): mixed;
}