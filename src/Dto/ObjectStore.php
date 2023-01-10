<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Dto;

use Webmozart\Assert\Assert;

use function class_implements;
use function class_parents;

final class ObjectStore implements ObjectStoreInterface
{
    /**
     * @param list<ObjectStoreItem> $objects
     */
    private function __construct(private array $objects = []) {}

    public static function create(): ObjectStoreInterface
    {
        return new self();
    }

    /**
     * @param class-string $class
     */
    public function append(string $class, object $object): void
    {
        $interfaces = class_implements($object);
        Assert::isArray($interfaces);
        $abstracts = class_parents($object);
        Assert::isArray($abstracts);

        $this->objects[] = ObjectStoreItem::create($class, $object::class, $object, $interfaces, $abstracts);
    }

    /**
     * @param class-string $class
     */
    public function searchById(string $class): null|object
    {
        foreach ($this->objects as $object) {
            $object = $object->searchByGivenId($class);
            if ($object !== null) {
                return $object;
            }
        }

        return null;
    }
}
