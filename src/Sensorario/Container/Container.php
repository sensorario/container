<?php

namespace Sensorario\Container;

use ReflectionClass;

class Container
{
    private $services = [];

    private $builder;

    private $instances = [];

    public function setArgumentBuilder(ArgumentBuilder $builder)
    {
        $this->builder = $builder;
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

    private function ensureServiceIsDefined($serviceName)
    {
        if (!$this->contains($serviceName)) {
            throw new \RuntimeException(
                'Oops! Service ' . $serviceName . ' not defined'
            );
        }
    }

    private function ensureBuilderIsDefined()
    {
        if (!$this->builder) {
            throw new \RuntimeException(
                'Oops! No builder, no party!'
            );
        }
    }

    public function get(string $serviceName)
    {
        $this->ensureServiceIsDefined($serviceName);

        $service = Objects\Service::box([
            'name' => $serviceName,
            'services' => $this->services,
        ]);

        if ($service->isConstructorEmpy()) {
            if ($service->classNotExists()) {
                throw new \RuntimeException(
                    'Oops! Class ' . $service->getClass() .
                    ' defined as ' . $service->getName()
                );
            }

            $serviceClass = $service->getClass();

            return new $serviceClass();
        }

        $this->ensureBuilderIsDefined();

        $this->builder->setParams($service->getParams());

        if (!isset($this->instances[$service->getName()])) {
            $this->instances[$service->getName()] = (new ReflectionClass($service->getClass()))
                ->newInstanceArgs($this->builder->getArguments());
        }

        return $this->instances[$service->getName()];
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
