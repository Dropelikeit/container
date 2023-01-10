<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Loader;

use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ClassStoreInterface;

use Webmozart\Assert\Assert;
use function file_exists;
use function str_contains;
use function array_walk;

final class PHPArrayLoader implements FileLoader
{
    public function loadFileFromPath(string $path, ClassStoreInterface $classStore): ClassStoreInterface
    {
        if (!file_exists($path)) {
            throw new \LogicException('File does not exist.');
        }

        if (!str_contains($path, '.php')) {
            throw new \LogicException('This loader need .php-config files.');
        }

        $services = (array) include $path ?? [];

        foreach ($services as $class => $classInformation) {
            $item = ClassStore\ClassItem::create($classInformation);
            $classStore->append($item);
        }

        array_walk($services, static function (array $serviceInformation) use ($classStore): void {
            $class = (string) key($serviceInformation);
            Assert::classExists($class);

            $serviceInformation[$key]

            $classStore->append();
        });

        return $classStore;
    }

    public function loadFileFromPaths(array $paths, ClassStoreInterface $classStore): ClassStoreInterface
    {
        array_walk($paths, [$this, 'loadFileFromPath']);

        return $classStore;
    }
}