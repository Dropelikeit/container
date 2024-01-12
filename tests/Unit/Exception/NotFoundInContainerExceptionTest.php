<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Exception;

use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

use function sprintf;

#[CoversClass(className: NotFoundInContainerException::class)]
final class NotFoundInContainerExceptionTest extends TestCase
{
    #[Test]
    public function canInitialize(): void
    {
        $dummy = new class() {};

        $message = sprintf(
            'Can not found a entry in container with given id "%s"',
            $dummy::class
        );

        $exception = NotFoundInContainerException::create($dummy::class, null);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(500, $exception->getCode());
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertInstanceOf(NotFoundExceptionInterface::class, $exception);
    }
}
