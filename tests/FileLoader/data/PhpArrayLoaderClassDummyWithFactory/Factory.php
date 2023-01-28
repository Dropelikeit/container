<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;

use MarcelStrahl\Tests\FileLoader\data\PhpArrayLoaderClassDummyWithFactory;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class Factory
{
    public function __invoke(): PhpArrayLoaderClassDummyWithFactory
    {
        return new PhpArrayLoaderClassDummyWithFactory();
    }
}
