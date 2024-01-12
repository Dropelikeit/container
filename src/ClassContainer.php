<?php

declare(strict_types=1);

namespace MarcelStrahl\Container;

use MarcelStrahl\Container\Contract\ClassContainerInterface;
use MarcelStrahl\Container\Contract\Dto\ClassStore\ClassItemInterface;
use MarcelStrahl\Container\Contract\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;

final class ClassContainer implements ClassContainerInterface
{
    private bool $compiled;

    private function __construct(private ClassStoreInterface $classes)
    {
        $this->compiled = false;
    }

    public static function create(ClassStoreInterface $classStore): self
    {
        return new self(classes: $classStore);
    }

    public function append(ClassItemInterface $classItem): void
    {
        $this->classes->append($classItem);
    }

    public function isCompiled(): bool
    {
        return $this->compiled;
    }

    public function compile(): void
    {
        $this->compiled = true;
    }

    public function get(string $id): ClassItemInterface
    {
        $metadata = $this->classes->searchById($id);
        if (!$metadata instanceof ClassItemInterface) {
            throw NotFoundInContainerException::create($id, null);
        }

        return $metadata;
    }

    public function has(string $id): bool
    {
        return $this->classes->hasEntry($id);
    }
}
