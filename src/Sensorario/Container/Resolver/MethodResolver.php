<?php

namespace Sensorario\Container\Resolver;

use ReflectionClass;
use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Objects\Service;

class MethodResolver implements
    \Sensorario\Container\Resolver\ResolverInterface
{
    private $instances = array();

    private $builder;

    public function setArgumentBuilder(ArgumentBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function resolve(Service $service)
    {
        $this->builder->setParams($service->getParams());

        $this->arguments = $this->builder->getArguments();

        if (!isset($this->instances[$service->getName()])) {
            $refObj = new ReflectionClass($service->getClass());
            $this->instances[$service->getName()] = $refObj->newInstanceArgs($this->arguments);
        }

        return $this->instances[$service->getName()];
    }
}
