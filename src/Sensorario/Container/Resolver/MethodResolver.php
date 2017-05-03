<?php

namespace Sensorario\Container\Resolver;

use ReflectionClass;
use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Objects\Service;

class MethodResolver
{
    private $instances = array();

    public function resolve(Service $service, ArgumentBuilder $builder)
    {
        $builder->setParams($service->getParams());

        $arguments = $builder->getArguments();

        if (!isset($this->instances[$service->getName()])) {
            $refObj = new ReflectionClass($service->getClass());
            $this->instances[$service->getName()] = $refObj->newInstanceArgs($arguments);
        }

        return $this->instances[$service->getName()];
    }
}
