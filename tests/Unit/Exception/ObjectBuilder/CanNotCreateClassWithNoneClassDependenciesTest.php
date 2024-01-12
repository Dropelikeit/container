<?php

declare(strict_types=1);

namespace MarcelStrahl\Tests\Unit\Exception\ObjectBuilder;

use LogicException;
use MarcelStrahl\Container\Exception\ObjectBuilder\CanNotCreateClassWithNoneClassDependencies;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CanNotCreateClassWithNoneClassDependenciesTest extends TestCase
{
    #[Test]
    public function canInitialize(): void
    {
        $exception = CanNotCreateClassWithNoneClassDependencies::create('some-type');

        $this->assertInstanceOf(LogicException::class, $exception);
        $this->assertSame(
            'Currently, no object can be created with non-class dependencies, given type "some-type".',
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }
}
