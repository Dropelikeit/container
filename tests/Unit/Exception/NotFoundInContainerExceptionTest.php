<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Exception;

use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @internal
 *

 */
final class NotFoundInContainerExceptionTest extends TestCase
{
    public function testCanInitialize(): void
    {
        $dummy = new class() {};

        $message = sprintf(
            'Can not found a entry in container with given id "%s"',
            $dummy::class
        );

        $exception = NotFoundInContainerException::create($dummy::class, null);

        static::assertSame($message, $exception->getMessage());
        static::assertSame(500, $exception->getCode());
        static::assertInstanceOf(\RuntimeException::class, $exception);
        static::assertInstanceOf(NotFoundExceptionInterface::class, $exception);
    }
}
