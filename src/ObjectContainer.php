<?php
declare(strict_types=1);

namespace MarcelStrahl\Container;

use LogicException;
use MarcelStrahl\Container\ObjectBuilder\ObjectBuilder;
use MarcelStrahl\Container\Dto\ObjectStoreInterface;
use MarcelStrahl\Container\Exception\NotFoundInContainerException;
use Psr\Container\ContainerInterface;

final class ObjectContainer implements ContainerInterface
{
    public function __construct(
        private /*readonly*/ ObjectStoreInterface $objectStore,
        private /*readonly*/ ObjectBuilder $builder,
    ) {}

    /**
     * @param class-string $id
     */
    public function get(string $id): object
    {
        $object = $this->objectStore->searchById($id);
        if ($object !== null) {
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

    /**
     * @param class-string $id
     */
    public function has(string $id): bool
    {
        return $this->objectStore->searchById($id) !== null;
    }
}