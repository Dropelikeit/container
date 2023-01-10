<?php
declare(strict_types=1);

namespace MarcelStrahl\Container;

use MarcelStrahl\Container\Dto\ClassStore\ClassItemInterface;
use MarcelStrahl\Container\Dto\ClassStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;

final class ClassContainer implements ClassContainerInterface
{
    private bool $compiled;
    private function __construct(private ClassStoreInterface $classes) {
        $this->compiled = false;
    }

    public static function create(ClassStoreInterface $classStore): self
    {
        return new self(classes: $classStore,);
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

    /**
     * @psalm-param class-string $id
     */
    public function get(string $id)
    {
        $class = $this->classes->searchById($id);
        if ($class === '') {
            throw NotFoundInContainerException::create($id, null);
        }
    }

    /**
     * @psalm-param class-string $id
     */
    public function has(string $id): bool
    {
        return $this->classes->hasEntry($id);
    }
}
