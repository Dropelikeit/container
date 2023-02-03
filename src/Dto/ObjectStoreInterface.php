<?php

declare(strict_types=1);

namespace MarcelStrahl\Container\Dto;

interface ObjectStoreInterface
{
    public static function create(): self;

    public function append(string $class, object $object): void;

    public function searchById(string $class): null|object;
}
