<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\ObjectBuilder;

use MarcelStrahl\Container\Exception\ObjectBuilderFactory\UnknownBuilderTypeException;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

final class ObjectBuilderFactory implements ObjectBuilderFactoryInterface
{
    private ?ContainerInterface $container;

    public function __construct()
    {
        $this->container = null;
    }

    /**
     * @psalm-param self::BUILDER_* $builderType
     * @throws UnknownBuilderTypeException
     */
    public function factorize(string $builderType): ObjectBuilder
    {
        $container = $this->container;

        Assert::isInstanceOf(
            $container,
            ContainerInterface::class,
            'It is important that you set the container before calling `factorize`.',
        );

        $reflectionBuilder = new ReflectionBuilder();

        return match ($builderType) {
            self::BUILDER_REFLECTION => $reflectionBuilder,
            self::BUILDER_FACTORY => new FactoryBuilder($container, $reflectionBuilder),
            default => throw UnknownBuilderTypeException::create($builderType),
        };
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
