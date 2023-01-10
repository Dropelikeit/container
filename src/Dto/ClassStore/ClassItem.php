<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto\ClassStore;

use Webmozart\Assert\Assert;

final class ClassItem implements ClassItemInterface
{
    private function __construct(
        private string $class,
        private string $id,
        private string $alias,
        private string $factory
    ) {}

    /**
     * @psalm-param non-empty-string $class
     */
    public static function create(string $class, array $classInformation): self
    {
        Assert::classExists($class);

        $serviceId = $classInformation['id'] ?? '';
        $alias = $classInformation['alias'] ?? '';
        $factory = $classInformation['factory'] ?? '';

        if ($serviceId === '') {
            $serviceId = $class;
        }

        if ($alias === '') {
            $alias = $class;
        }

        return new self($class, $serviceId, $alias, $factory);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function hasFactory(): bool
    {
        return $this->factory !== '';
    }

    public function getFactory(): string
    {
        return $this->factory;
    }

    public function hasAlias(): bool
    {
        if ($this->class === $this->alias) {
            return false;
        }

        return true;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
