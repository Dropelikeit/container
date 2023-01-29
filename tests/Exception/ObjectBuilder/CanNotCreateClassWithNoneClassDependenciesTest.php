<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Exception\ObjectBuilder;

use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *

 */
final class CanNotCreateClassWithNoneClassDependenciesTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $exception = CanNotCreateClassWithNoneClassDependencies::create('some-type');

        static::assertInstanceOf(\LogicException::class, $exception);
        static::assertSame(
            'Currently, no object can be created with non-class dependencies, given type "some-type".',
            $exception->getMessage()
        );
        static::assertSame(0, $exception->getCode());
        static::assertNull($exception->getPrevious());
    }
}
