<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\FileLoader;

use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NonExistingFileException;
use MarcelStrahl\Container\Exception\UnknownFileExtensionException;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 *
 * @internal
 */
final class PhpArrayAdapterTest extends TestCase
{
/**
 * @psalm-var MockObject&ClassStoreInterface
 */
    /* MockObject&ClassStoreInterface */ private $store;

    protected function setUp(): void
    {
        $this->store = $this->createMock(ClassStoreInterface::class);
    }

    public function testCanNotLoadFromFilePathWithNoneExistingFile(): void
    {
        $this->expectException(NonExistingFileException::class);

        $path = 'non/existing/config';

        $fileLoader = new PHPArrayAdapter();

        $fileLoader->loadFileFromPath($path, $this->store);
    }

    public function testCanNotLoadFromFilePathBecauseItHasNotRequiredPHPExtension(): void
    {
        $this->expectException(UnknownFileExtensionException::class);

        $path = sprintf('%s/config', __DIR__);
        static::assertNotEmpty($path);

        $fileLoader = new PHPArrayAdapter();

        $fileLoader->loadFileFromPath($path, $this->store);
    }

    public function testCanLoadFromFilePathSuccessful(): void
    {
        $path = sprintf('%s/php_array_config.php', __DIR__);
        static::assertNotEmpty($path);

        $content = include $path;

        $classItems = [];
        foreach ($content as $class => $item) {
            $classItems[] = [ClassItem::create($class, $item)];
        }

        $this->store->expects(static::exactly(4))->method('append')->withConsecutive(...$classItems);

        $fileLoader = new PHPArrayAdapter();
        $fileLoader->loadFileFromPath($path, $this->store);
    }

    public function testCanLoadFromMoreThanOneFilePaths(): void
    {
        $pathOne = sprintf('%s/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($pathOne);
        $pathTwo = sprintf('%s/array_config.php', __DIR__);
        Assert::stringNotEmpty($pathTwo);

        $paths = [
            $pathOne,
            $pathTwo,
        ];

        $classItems = [];
        foreach ($paths as $path) {
            $content = include $path;

            foreach ($content as $class => $item) {
                $classItems[] = [ClassItem::create($class, $item)];
            }
        }

        $this->store
            ->expects(static::exactly(7))
            ->method('append')
            ->withConsecutive(...$classItems)
        ;

        $fileLoader = new PHPArrayAdapter();
        $fileLoader->loadFileFromPaths($paths, $this->store);
    }
}
