<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\FileLoader;

use MarcelStrahl\Container\Contract\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Exception\NonExistingFileException;
use MarcelStrahl\Container\Exception\UnknownFileExtensionException;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

#[CoversClass(className: PHPArrayAdapter::class)]
#[UsesClass(className: ClassStoreInterface::class)]
#[UsesClass(className: NonExistingFileException::class)]
#[UsesClass(className: UnknownFileExtensionException::class)]
#[UsesClass(className: ClassItem::class)]
final class PhpArrayAdapterTest extends TestCase
{
    private readonly MockObject&ClassStoreInterface $store;

    protected function setUp(): void
    {
        $this->store = $this->getMockBuilder(ClassStoreInterface::class)->getMock();
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
        $this->assertNotEmpty($path);

        $fileLoader = new PHPArrayAdapter();

        $fileLoader->loadFileFromPath($path, $this->store);
    }

    public function testCanLoadFromFilePathSuccessful(): void
    {
        $path = sprintf('%s/php_array_config.php', __DIR__);
        $this->assertNotEmpty($path);

        $this->store->expects(self::exactly(4))->method('append');

        $fileLoader = new PHPArrayAdapter();
        $fileLoader->loadFileFromPath($path, $this->store);
    }

    public function testCanLoadFromMoreThanOneFilePaths(): void
    {
        $pathOne = sprintf('%s/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($pathOne);
        $pathTwo = sprintf('%s/array_config.php', __DIR__);
        Assert::stringNotEmpty($pathTwo);

        $this->store
            ->expects(self::exactly(7))
            ->method('append')
        ;

        $fileLoader = new PHPArrayAdapter();
        $fileLoader->loadFileFromPaths([
            $pathOne,
            $pathTwo,
        ], $this->store);
    }
}
