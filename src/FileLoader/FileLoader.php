<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\FileLoader;

use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NonExistingFileException;

interface FileLoader
{
    /**
     * @psalm-param non-empty-string $path
     * @throws NonExistingFileException
     */
    public function loadFileFromPath(string $path, ClassStoreInterface $classStore): ClassStoreInterface;

    /**
     * @psalm-param non-empty-list<non-empty-string> $paths
     * @throws NonExistingFileException
     */
    public function loadFileFromPaths(array $paths, ClassStoreInterface $classStore): ClassStoreInterface;
}
