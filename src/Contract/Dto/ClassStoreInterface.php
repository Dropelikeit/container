<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Contract\Dto;

use MarcelStrahl\Container\Contract\Dto\ClassStore\ClassItemInterface;

interface ClassStoreInterface
{
    public static function create(): self;

    /**
     * @psalm-param class-string $id
     * @psalm-param class-string $class
     */
    public function append(ClassItemInterface $classItem): void;

    /**
     * @psalm-param class-string $id
     *
     * @psalm-return ''|class-string
     */
    public function searchById(string $id): ?ClassItemInterface;

    /**
     * @psalm-param class-string $id
     */
    public function hasEntry(string $id): bool;
}
