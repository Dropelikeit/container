<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Exception;

use MarcelStrahl\Container\Exception\NotInitializedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *

 */
final class NotInitializedExceptionTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $dummy = new class() {};

        $message = sprintf(
            'Class must be initialized before you ask the object container after an object of "%s"',
            $dummy::class
        );

        $exception = NotInitializedException::create($dummy::class);

        static::assertSame($message, $exception->getMessage());
        static::assertSame(0, $exception->getCode());
        static::assertInstanceOf(\LogicException::class, $exception);
    }
}
