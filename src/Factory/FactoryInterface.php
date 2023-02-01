<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Factory;

use Psr\Container\ContainerInterface;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
interface FactoryInterface
{
    public function factorize(ContainerInterface $container): object;
}
