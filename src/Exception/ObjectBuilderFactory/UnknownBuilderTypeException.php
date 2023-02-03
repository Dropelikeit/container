<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Exception\ObjectBuilderFactory;

use RuntimeException;

final class UnknownBuilderTypeException extends RuntimeException
{
    public static function create(string $type): self
    {
        return new self(sprintf('Unknown builder type detected. Given: "%s"', $type));
    }
}
