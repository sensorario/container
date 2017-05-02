<?php

namespace Sensorario\Container;

use ReflectionClass;

class MethodResolver
{
    private $instances = array();

    public function resolve($service, ArgumentBuilder $builder)
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
