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

        $this->builder = new ArgumentBuilder();
        $this->builder->setContainer($this);

        $this->methodResolver->setArgumentBuilder(
            $this->builder
        );
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

    public function get($serviceName)
    {
        $this->ensureServiceIsDefined($serviceName);

        $service = Objects\Service::box(array(
            'name' => $serviceName,
            'services' => $this->services,
        ));

        if ($service->isConstructorInjection()) {
            return $this->methodResolver->resolve($service);
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
