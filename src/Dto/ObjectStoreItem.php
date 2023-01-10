<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto;

use function in_array;

final class ObjectStoreItem
{
    /**
     * @psalm-param class-string $class
     * @psalm-param list<class-string> $interfaces
     * @psalm-param list<class-string> $abstracts
     */
    private function __construct(
        private /*readonly*/ string $id,
        private /*readonly*/ string $class,
        private /*readonly*/ object $instance,
        private /*readonly*/ array  $interfaces,
        private /*readonly*/ array  $abstracts,
    ) {}

    /**
     * @psalm-param string|class-string $id
     * @psalm-param class-string $class
     * @psalm-param list<class-string> $interfaces
     * @psalm-param list<class-string> $abstracts
     */
    public static function create(
        string $id,
        string $class,
        object $object,
        array $interfaces,
        array $abstracts
    ): self {
        return new self(
            id: $id,
            class: $class,
            instance: $object,
            interfaces: $interfaces,
            abstracts: $abstracts,
        );
    }

    public function searchByGivenId(string $id): ?object
    {
        if ($this->searchById($id)) {
            return $this->instance;
        }

        if ($this->searchByClass($id)) {
            return $this->instance;
        }

        if ($this->searchByInterface($id)) {
            return $this->instance;
        }

        if ($this->searchByAbstract($id)) {
            return $this->instance;
        }

        return null;
    }

    private function searchById(string $id): bool
    {
        return $this->id === $id;
    }

    private function searchByClass(string $class): bool
    {
        return $this->class === $class;
    }

    private function searchByInterface(string $interface): bool
    {
        return in_array($interface, $this->interfaces, true);
    }

    private function searchByAbstract(string $abstract): bool
    {
        return in_array($abstract, $this->abstracts, true);
    }
}