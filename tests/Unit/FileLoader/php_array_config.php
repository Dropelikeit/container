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
];
