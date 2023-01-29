<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Exception\ObjectBuilder;

use LogicException;

final class CanNotCreateClassWithNoneClassDependencies extends LogicException
{
    public static function create(string $type): self
    {
        return new self(sprintf(
            'Currently, no object can be created with non-class dependencies, given type "%s".',
            $type
        ));
    }
}
