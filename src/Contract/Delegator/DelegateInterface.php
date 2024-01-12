<?php
declare(strict_types=1);

namespace MarcelStrahl\Container\Contract\Delegator;

interface DelegateInterface
{
    public function delegate(string $class): object;
}
