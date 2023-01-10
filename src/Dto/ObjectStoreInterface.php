<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto;

interface ObjectStoreInterface
{
    public static function create(): self;

    /**
     * @param class-string $class
     */
    public function append(string $class, object $object): void;

    /**
     * @param class-string $class
     */
    public function searchById(string $class): null|object;
}