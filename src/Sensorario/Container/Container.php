<?php

namespace Sensorario\Container;

use ReflectionClass;

class Container
{
    private $services = [];

    private $builder;

    public function setArgumentBuilder(ArgumentBuilder $builder)
    {
        $this->builder = $builder;

        /** @todo this is not covered */
        $this->builder->setContainer($this);
    }

    public function loadServices(array $services)
    {
        $this->services = $services;
    }

    public function getServicesConfiguration()
    {
        return $this->services;
    }

    public function get($serviceName)
    {
        if (!$this->contains($serviceName)) {
            throw new \RuntimeException(
                'Oops! Service not defined'
            );
        }

        $serviceClass = $this->services[$serviceName]['class'];

        if (!isset($this->services[$serviceName]['params'])) {
            return new $serviceClass();
        }

        if (!$this->builder) {
            throw new \RuntimeException(
                'Oops! No builder, no party!'
            );
        }

        $params = $this->services[$serviceName]['params'];

        $this->builder->setParams($params);

        $arguments = $this->builder->getArguments();

        return (new ReflectionClass($serviceClass))
            ->newInstanceArgs($arguments);
    }

    public function contains($serviceName)
    {
        return isset($this->services[$serviceName]);
    }

    public function hasArguments($serviceName)
    {
        return isset($this->services[$serviceName]['params']);
    }
}
