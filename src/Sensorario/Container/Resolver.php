<?php

namespace Sensorario\Container;

use ReflectionClass;

class Resolver
{
    private $instances = [];

    public function resolve($service, ArgumentBuilder $builder)
    {
        $builder->setParams($service->getParams());

        $arguments = $builder->getArguments();

        if (!isset($this->instances[$service->getName()])) {
            $this->instances[$service->getName()] = (new ReflectionClass($service->getClass()))
                ->newInstanceArgs($arguments);
        }

        return $this->instances[$service->getName()];
    }

    public function simpleResolver($service)
    {
        if ($service->classNotExists()) {
            throw new \RuntimeException(
                'Oops! Class ' . $service->getClass() .
                ' defined as ' . $service->getName()
            );
        }

        $serviceClass = $service->getClass();

        return new $serviceClass();
    }
}
