<?php

declare(strict_types=1);

namespace MarcelStrahl\Container;

use LogicException;
use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use Psr\Container\ContainerInterface;

final class ObjectContainer implements ContainerInterface
{
    public function __construct(
        private /* readonly */ ObjectStoreInterface $objectStore,
        private /* readonly */ ObjectBuilder $builder,
    ) {
    }

    public function get(string $id): object
    {
        $object = $this->objectStore->searchById($id);
        if (null !== $object) {
            return $object;
        }

        try {
            $object = $this->builder->initialize($id);
        } catch (LogicException $exception) {
            throw NotFoundInContainerException::create($id, $exception);
        }

        $this->objectStore->append($id, $object);

        return $object;
    }

    public function has(string $id): bool
    {
        return null !== $this->objectStore->searchById($id);
    }
}
