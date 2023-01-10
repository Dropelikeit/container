<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto\ClassStore;

interface ClassItemInterface
{
    /**
     * @psalm-return non-empty-string
     */
    public function getId(): string;

    public function getClass(): string;

    public function hasFactory(): bool;

    public function getFactory(): string;

    public function hasAlias(): bool;

    /**
     * @psalm-return non-empty-string
     */
    public function getAlias(): string;
}
