<?php

namespace Sensorario\Container;

use ReflectionClass;

class Container
{
    private $services = array();

    private $builder;

    private $resolver;

    public function __construct()
    {
        $this->resolver = new Resolver();
    }

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

    public function get($serviceName)
    {
        $this->ensureServiceIsDefined($serviceName);

        $service = Objects\Service::box(array(
            'name' => $serviceName,
            'services' => $this->services,
        ));

        if ($service->isMethodInjection()) {
            return $this->resolver->methods($service);
        }

        if ($service->isConstructorInjection()) {
            $this->ensureBuilderIsDefined();
            return $this->resolver->resolve($service, $this->builder);
        }

        if ($service->isConstructorEmpty()) {
            return $this->resolver->simpleResolver($service);
        }

        throw new \RuntimeException(
            'Oops!'
        );
    }

    public function contains($serviceName)
    {
        $service = Objects\Argument::fromString($serviceName);
        return isset($this->services[$service->getServiceName()]);
    }

    public function hasArguments($serviceName)
    {
        return isset($this->services[$serviceName]['params']);
    }
}
