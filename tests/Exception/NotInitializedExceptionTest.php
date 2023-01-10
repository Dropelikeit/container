<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Exception;

use LogicException;
use MarcelStrahl\Container\Exception\NotInitializedException;
use PHPUnit\Framework\TestCase;

use function sprintf;

final class NotInitializedExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function canInitialize(): void
    {
        $dummy = new class() {};

        $message = sprintf(
            'Class must be initialized before you ask the object container after an object of "%s"',
            $dummy::class
        );

        $exception = NotInitializedException::create($dummy::class);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertInstanceOf(LogicException::class, $exception);
    }
}