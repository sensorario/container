<?php

namespace Sensorario\Container\Resolver;

use ReflectionClass;
use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Objects\Service;

class MethodResolver implements
    \Sensorario\Container\Resolver\ResolverInterface
{
    private $builder;

    public function setArgumentBuilder(ArgumentBuilder $builder) : void
    {
        $this->builder = $builder;
    }

    public function resolve(Service $service)
    {
        $this->builder->setParams($service->getParams());

        $arguments = $this->builder->getArguments();

        $refObj = new ReflectionClass($service->getClass());

        return $refObj->newInstanceArgs($arguments);
    }
}
