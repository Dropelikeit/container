<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\FileLoader;

use function array_walk;
use function call_user_func_array;
use function file_exists;
use MarcelStrahl\Container\Dto\ClassStore;

use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NonExistingFileException;
use MarcelStrahl\Container\Exception\UnknownFileExtensionException;
use function str_contains;

final class PHPArrayAdapter implements FileLoader
{
    public function loadFileFromPath(string $path, ClassStoreInterface $classStore): ClassStoreInterface
    {
        if (!file_exists($path)) {
            throw NonExistingFileException::createByMissingFile($path);
        }

        if (!str_contains($path, '.php')) {
            throw UnknownFileExtensionException::createByUnknownFileExtension(
                $path,
                '.php'
            );
        }

        $services = (array) include $path ?? [];

        foreach ($services as $class => $classInformation) {
            $classStore->append(ClassStore\ClassItem::create($class, $classInformation));
        }

        return $classStore;
    }

    public function loadFileFromPaths(array $paths, ClassStoreInterface $classStore): ClassStoreInterface
    {
        $instance = $this;
        array_walk(
            $paths,
            static function (string $path) use ($instance, $classStore): void {
                call_user_func_array([$instance, 'loadFileFromPath'], [$path, $classStore]);
            }
        );

        return $classStore;
    }
}
