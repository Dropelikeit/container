<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class CannotRetrieveException extends RuntimeException implements ContainerExceptionInterface
{
    /**
     * @psalm-param class-string $class
     */
    public static function create(string $class): self
    {
        $message = sprintf('Cannot initialize object "%s"', $class);
        Assert::stringNotEmpty($message);

        return new self($message);
    }
}