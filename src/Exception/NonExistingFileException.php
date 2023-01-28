<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Exception;

use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class NonExistingFileException extends \RuntimeException
{
    /**
     * @psalm-param non-empty-string $file
     */
    public static function createByMissingFile(string $file): self
    {
        $message = sprintf('Missing required file "%s"', $file);
        Assert::stringNotEmpty($message);

        return new self($message, 500);
    }
}
