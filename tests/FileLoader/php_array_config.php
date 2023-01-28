<?php
declare(strict_types=1);

return [
    // class => [factory?: class-string, alias?: class-string, id?: non-empty-string]
    \MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummy::class => [],
    \MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory::class => [
        'factory' => \MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory\Factory::class,
    ],
    \MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithServiceId::class => [
        'id' => 'serviceId',
    ],
    \MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithAlias::class => [
        'alias' => \MarcelStrahl\Tests\FileLoader\data\AliasInterface::class,
    ],
];