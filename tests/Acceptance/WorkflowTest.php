<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Acceptance;

use MarcelStrahl\Container\AppContainer;
use MarcelStrahl\Container\ClassContainer;
use MarcelStrahl\Container\Delegator\ObjectDelegator;
use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactory;
use MarcelStrahl\Container\ObjectContainer;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummy;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 *
 * @internal
 */
final class WorkflowTest extends TestCase
{
    public function testCanLoadContainerWithOneServiceFile(): void
    {
        $adapter = (new AdapterBuilder())->build(PHPArrayAdapter::class);

        $path = sprintf('%s/../Unit/FileLoader/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($path);

        $builderFactory = new ObjectBuilderFactory();
        $delegator = new ObjectDelegator($builderFactory);

        $classStore = $adapter->loadFileFromPath($path, ClassStore::create());

        $classContainer = ClassContainer::create($classStore);
        $classContainer->compile();

        $objectContainer = new ObjectContainer(ObjectStore::create(), $delegator);

        $app = AppContainer::initialize($classContainer, $objectContainer);
        $builderFactory->setContainer($app);

        static::assertTrue($app->has(PhpArrayLoaderClassDummy::class));
        $object = $app->get(PhpArrayLoaderClassDummy::class);

        static::assertInstanceOf(PhpArrayLoaderClassDummy::class, $object);
    }

    public function testCanLoadContainerWithMoreThanOneServiceFile(): void
    {
        $adapter = (new AdapterBuilder())->build(PHPArrayAdapter::class);

        $pathOne = sprintf('%s/../Unit/FileLoader/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($pathOne);

        $pathTwo = sprintf('%s/../Unit/FileLoader/array_config.php', __DIR__);
        Assert::stringNotEmpty($pathTwo);

        $builderFactory = new ObjectBuilderFactory();
        $delegator = new ObjectDelegator($builderFactory);

        $classStore = $adapter->loadFileFromPaths([$pathOne, $pathTwo], ClassStore::create());

        $classContainer = ClassContainer::create($classStore);
        $classContainer->compile();

        $objectContainer = new ObjectContainer(ObjectStore::create(), $delegator);

        $app = AppContainer::initialize($classContainer, $objectContainer);
        $builderFactory->setContainer($app);

        static::assertTrue($app->has(PhpArrayLoaderClassDummy::class));
        $object = $app->get(PhpArrayLoaderClassDummy::class);

        static::assertInstanceOf(PhpArrayLoaderClassDummy::class, $object);

        static::assertTrue($app->has(Factory::class));
        $object = $app->get(Factory::class);

        static::assertInstanceOf(PhpArrayLoaderClassDummyWithFactory::class, $object);
    }
}
