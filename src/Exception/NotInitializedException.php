<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Exception;

final class NotInitializedException extends \LogicException
{
    /**
     * @param class-string $class
     */
    public static function create(string $class): self
    {
        return new self(sprintf(
            'Class must be initialized before you ask the object container after an object of "%s"',
            $class,
        ));
    }
}
