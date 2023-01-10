<?php
declare(strict_types=1);

namespace MarcelStrahl\Container;

use Psr\Container\ContainerInterface;

final class AppContainer implements ContainerInterface
{
    private function __construct(
        ClassContainerInterface $classContainer,
        ContainerInterface $objectContainer,
    ) {
    }

    public static function initialize(): self
    {
        return new self();
    }

    public function get(string $id): string
    {


        return '';
    }

    public function has(string $id): bool
    {
        return false;
    }
}