<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class NotFoundInContainerException extends RuntimeException implements NotFoundExceptionInterface
{
    public static function create(string $id, ?\LogicException $previousException = null): self
    {
        $message = sprintf('Can not found a entry in container with given id "%s"', $id);
        Assert::stringNotEmpty($message);

        return new self(message: $message, code: 500, previous: $previousException);
    }
}
