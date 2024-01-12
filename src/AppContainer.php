<?php

declare(strict_types=1);

namespace MarcelStrahl\Container;

use MarcelStrahl\Container\Contract\ClassContainerInterface;
use MarcelStrahl\Container\Dto\ClassStore\ClassItem;
use MarcelStrahl\Container\Exception\CannotRetrieveException;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use Psr\Container\ContainerInterface;

final class AppContainer implements ContainerInterface
{
    private function __construct(
        private /* readonly */ ClassContainerInterface $classContainer,
        private /* readonly */ ContainerInterface $objectContainer,
    ) {
    }

    public static function initialize(
        ClassContainerInterface $classContainer,
        ContainerInterface $objectContainer
    ): self {
        return new self(classContainer: $classContainer, objectContainer: $objectContainer);
    }

    public function get(string $id): mixed
    {
        if (!$this->classContainer->has($id)) {
            throw NotFoundInContainerException::create($id);
        }

        /** @var ClassItem $metadata */
        $metadata = $this->classContainer->get($id);

        $class = $metadata->getClass();
        if ($metadata->hasFactory()) {
            $class = $metadata->getFactory();
        }

        try {
            return $this->objectContainer->get($class);
        } catch (NotFoundInContainerException) {
            throw CannotRetrieveException::create($metadata->getClass());
        }
    }

    public function has(string $id): bool
    {
        if ($this->objectContainer->has($id)) {
            return true;
        }

        if ($this->classContainer->has($id)) {
            return true;
        }

        return false;
    }
}
