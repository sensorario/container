<?php

namespace Sensorario\Container;

use Sensorario\Container\Objects\Service;
use Sensorario\Container\Resolver\ResolverInterface;

class Register
{
    private $instances = [];

    public function has(Service $service)
    {
        return isset($this->instances[$service->getName()]);
    }

    public function register(Service $service, ResolverInterface $resolver)
    {
        $this->instances[$service->getName()] = $resolver->resolve($service);
    }

    public function get(Service $service)
    {
        return $this->instances[$service->getName()];
    }
}
