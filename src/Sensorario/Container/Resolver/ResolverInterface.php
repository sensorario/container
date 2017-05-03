<?php

namespace Sensorario\Container\Resolver;

use Sensorario\Container\Objects\Service;

interface ResolverInterface
{
    public function resolve(Service $service);
}
