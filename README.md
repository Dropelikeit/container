PHP DI Container
----------------

A lightweight dependency injection container that is decoupled from any framework.
This library can be used for production, but I recommend that it is better to use a di-container from a larger community like Symfony, Laravel or Laminas.

This library was developed for fun, but is supported if someone uses it.

This project can also be used as an example to show how a modern DI container works in a simplified way.

---------------

How it works

```bash
    composer require marcel-strahl/container
```

Written for PHP >= 8.0

This library use the PSR container.

--------------

Usage:

```php
<?php

use MarcelStrahl\Container\FileLoader\AdapterBuilder;
use MarcelStrahl\Container\FileLoader\PHPArrayAdapter;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilderFactory;
use MarcelStrahl\Container\Delegator\ObjectDelegator;
use MarcelStrahl\Container\ClassContainer;
use MarcelStrahl\Container\ObjectContainer;
use MarcelStrahl\Container\AppContainer;
use MarcelStrahl\Container\Dto\ObjectStore;
use MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummy;

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

// Insert `$app` into your application context
```

You need to follow config structure. It is good to know that you can also specify an empty array 
if you do not want to specify an alias or a factory.

```php
<?php

<?php

declare(strict_types=1);

return [
    // class => [factory?: class-string, alias?: class-string, id?: non-empty-string]
    \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummy::class => [],
    \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory::class => [
        'factory' => \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory::class,
    ],
    \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithServiceId::class => [
        'id' => 'serviceId',
    ],
    \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithAlias::class => [
        'alias' => \MarcelStrahl\Tests\Unit\FileLoader\data\AliasInterface::class,
    ],
    \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory::class => [
        'alias' => \MarcelStrahl\Tests\Unit\FileLoader\data\AliasInterface::class,
        'id' => 'serviceId',
        'factory' => \MarcelStrahl\Tests\Unit\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory::class,
    ],
];
```

