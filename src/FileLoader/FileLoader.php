<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Loader;

use MarcelStrahl\Container\Dto\ClassStoreInterface;

interface FileLoader
{
    /**
     * @psalm-param non-empty-string $path
     */
    public function loadFileFromPath(string $path, ClassStoreInterface $classStore): ClassStoreInterface;

    /**
     * @psalm-param non-empty-list<non-empty-string> $paths
     */
    public function loadFileFromPaths(array $paths, ClassStoreInterface $classStore): ClassStoreInterface;
}
