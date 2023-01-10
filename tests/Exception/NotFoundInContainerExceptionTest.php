<?php
declare(strict_types=1);

namespace MarcelStrahl\Tests\Exception;

use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

use function sprintf;

final class NotFoundInContainerExceptionTest extends TestCase
{
    /**
     * @test
     */
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