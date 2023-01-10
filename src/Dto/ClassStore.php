<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto;

use MarcelStrahl\Container\Dto\ClassStore\ClassItemInterface;

final class ClassStore implements ClassStoreInterface
{
    /**
     * @param array<class-string, ClassItemInterface> $classes
     */
    private function __construct(private array $classes = []) {}

    public static function create(): self
    {
        return new self();
    }

    public function append(ClassItemInterface $classItem): void
    {
        $key = $classItem->getId();
        if ($classItem->hasAlias()) {
            $key = $classItem->getAlias();
        }

        $this->classes[$key] = $classItem;
    }

    /**
     * @psalm-param class-string $id
     * @psalm-return ''|class-string
     */
    public function searchById(string $id): string
    {
        $classItem = $this->classes[$id] ?? null;
        if ($classItem instanceof ClassItemInterface) {
            return $classItem->getClass();
        }

        return $this->matchClassItemById($id)?->getClass() ?? '';
    }

    /**
     * @psalm-param class-string $id
     */
    public function hasEntry(string $id): bool
    {
        return $this->matchClassItemById($id) instanceof ClassItemInterface;
    }

    /**
     * @psalm-param non-empty-string $id
     */
    private function matchClassItemById(string $id): ?ClassItemInterface
    {
        foreach ($this->classes as $classItem) {
            if (
                $classItem->getId() === $id
                || $classItem->getAlias() === $id
                || $classItem->getClass() === $id
            ) {
                return $classItem;
            }
        }

        return null;
    }
}