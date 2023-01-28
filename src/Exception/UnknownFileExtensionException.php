<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Exception;

use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class UnknownFileExtensionException extends \RuntimeException
{
    /**
     * @psalm-param non-empty-string $path
     * @psalm-param non-empty-string $acceptedExtension
     */
    public static function createByUnknownFileExtension(string $path, string $acceptedExtension): self
    {
        $message = sprintf(
            'Given file "%s" must be have following file extension "%s"',
            $path,
            $acceptedExtension
        );
        Assert::stringNotEmpty($message);

        return new self($message);
    }
}
