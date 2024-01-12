<?php

declare(strict_types=1);

namespace MarcelStrahl\Container;

use LogicException;
use MarcelStrahl\Container\Contract\Delegator\DelegateInterface;
use MarcelStrahl\Container\Contract\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use Psr\Container\ContainerInterface;

final class ObjectContainer implements ContainerInterface
{
    public function __construct(
        private /* readonly */ ObjectStoreInterface $objectStore,
        private /* readonly */ DelegateInterface $delegator,
    ) {
    }

    public function get(string $id): object
    {
        $object = $this->objectStore->searchById($id);
        if (null !== $object) {
            return $object;
        }

        try {
            $object = $this->delegator->delegate($id);
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
