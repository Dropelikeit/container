<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Contract;

use MarcelStrahl\Container\Contract\Dto\ClassStore\ClassItemInterface;
use Psr\Container\ContainerInterface;

interface ClassContainerInterface extends ContainerInterface
{
    public function append(ClassItemInterface $classItem): void;

    public function isCompiled(): bool;

    public function compile(): void;
}
