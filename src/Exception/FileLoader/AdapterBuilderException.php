<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Exception\FileLoader;

use RuntimeException;

use Webmozart\Assert\Assert;
use function sprintf;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class AdapterBuilderException extends RuntimeException
{
    public static function createByUnknownAdapterType(string $adapter): self
    {
        $message = sprintf('Given unknown adapter type "%s"', $adapter);
        Assert::stringNotEmpty($message);

        return new self($message, 500);
    }
}