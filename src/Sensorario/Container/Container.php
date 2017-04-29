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

    public function get(string $serviceName)
    {
        if (!$this->contains($serviceName)) {
            throw new \RuntimeException(
                'Oops! Service ' . $serviceName . ' not defined'
            );
        }

        $argument = Objects\Argument::fromString($serviceName);
        $serviceName = $argument->getServiceName();

        $serviceClass = $this->services[$serviceName]['class'];

        if (!isset($this->services[$serviceName]['params'])) {
            if (!class_exists($serviceClass)) {
                throw new \RuntimeException(
                    'Oops! Class ' . $serviceClass .
                    ' defined as ' . $serviceName .
                    ' not exists in ' . var_export($this->services, true)
                );
            }

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

        $refClass = new ReflectionClass($serviceClass);

        return $refClass->newInstanceArgs($arguments);
    }

    public function contains(string $serviceName)
    {
        $service = Objects\Argument::fromString($serviceName);
        return isset($this->services[$service->getServiceName()]);
    }

    public function hasArguments($serviceName)
    {
        return isset($this->services[$serviceName]['params']);
    }
}
