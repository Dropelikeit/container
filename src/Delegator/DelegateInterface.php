<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Delegator;

interface DelegateInterface
{
    public function delegate(string $class): object;
}
