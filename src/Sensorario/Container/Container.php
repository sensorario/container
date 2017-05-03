<?php

namespace Sensorario\Container;

use Sensorario\Container\Resolver\ConstructorResolver;
use Sensorario\Container\Resolver\MethodResolver;
use Sensorario\Container\Resolver\Resolver;

class Container
{
    private $services = array();

    private $builder;

    private $resolver;

    private $construcrtorResolver;

    private $methodResolver;

    public function __construct()
    {
        $this->resolver = new Resolver();

        $this->resolver->setConstructorResolver(
            $this->construcrtorResolver = new ConstructorResolver()
        );

        $this->methodResolver = new MethodResolver();
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

        if ($service->isConstructorInjection()) {
            $this->ensureBuilderIsDefined();
            return $this->methodResolver->resolve($service, $this->builder);
        } else {
            if ($service->isMethodInjection()) {
                return $this->resolver->resolve($service);
            }

            return $this->construcrtorResolver->resolve($service);
        }
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
