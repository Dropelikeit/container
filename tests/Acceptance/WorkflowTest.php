<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Acceptance;

use MarcelStrahl\Container\AppContainer;
use MarcelStrahl\Container\ClassContainer;
use MarcelStrahl\Container\Dto\ClassStore;
use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\ObjectBuilder\ReflectionBuilder;
use MarcelStrahl\Container\ObjectContainer;
use MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummy;
use MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class WorkflowTest extends TestCase
{
    /**
     * @test
     */
    public function canLoadContainerWithOneServiceFile(): void
    {
        $adapter = (new AdapterBuilder())->build(PHPArrayAdapter::class);

        $path = sprintf('%s/../FileLoader/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($path);

        $classStore = $adapter->loadFileFromPath($path, ClassStore::create());

        $classContainer = ClassContainer::create($classStore);
        $classContainer->compile();

        $objectContainer = new ObjectContainer(ObjectStore::create(), new ReflectionBuilder());

        $app = AppContainer::initialize($classContainer, $objectContainer);

        $this->assertTrue($app->has(PhpArrayLoaderClassDummy::class));
        $object = $app->get(PhpArrayLoaderClassDummy::class);

        $this->assertInstanceOf(PhpArrayLoaderClassDummy::class, $object);
    }

    /**
     * @test
     */
    public function canLoadContainerWithMoreThanOneServiceFile(): void
    {
        $adapter = (new AdapterBuilder())->build(PHPArrayAdapter::class);

        $pathOne = sprintf('%s/../FileLoader/php_array_config.php', __DIR__);
        Assert::stringNotEmpty($pathOne);

        $pathTwo = sprintf('%s/../FileLoader/array_config.php', __DIR__);
        Assert::stringNotEmpty($pathTwo);

        $classStore = $adapter->loadFileFromPaths([$pathOne, $pathTwo], ClassStore::create());

        $classContainer = ClassContainer::create($classStore);
        $classContainer->compile();

        $objectContainer = new ObjectContainer(ObjectStore::create(), new ReflectionBuilder());

        $app = AppContainer::initialize($classContainer, $objectContainer);

        $this->assertTrue($app->has(PhpArrayLoaderClassDummy::class));
        $object = $app->get(PhpArrayLoaderClassDummy::class);

        $this->assertInstanceOf(PhpArrayLoaderClassDummy::class, $object);

        $this->assertTrue($app->has(Factory::class));
        $object = $app->get(Factory::class);

        $this->assertInstanceOf(Factory::class, $object);
    }
}