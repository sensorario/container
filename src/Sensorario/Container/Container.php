<?php

namespace Sensorario\Container;

use Sensorario\Container\Objects\Argument;
use Sensorario\Container\Objects\Service;
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
        $this->register = new Register();

        $this->construcrtorResolver = new ConstructorResolver();

        $this->resolver = new Resolver();
        $this->resolver->setConstructorResolver($this->construcrtorResolver);

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

        $service = Service::box(array(
            'name' => $serviceName,
            'services' => $this->services,
        ));

        if (!$this->register->has($service)) {
            $this->register->register(
                $service,
                $this->getResolver($service)
            );
        }

        return $this->register->get($service);
    }

    public function getResolver(Service $service)
    {
        if ($service->hasMethodInjection()) {
            return $this->methodResolver;
        }

        if ($service->hasSimpleInjection()) {
            return $this->resolver;
        }

        return $this->construcrtorResolver;
    }

    public function contains($serviceName)
    {
        $service = Argument::fromString($serviceName);
        return isset($this->services[$service->getServiceName()]);
    }

    public function hasArguments($serviceName)
    {
        return isset($this->services[$serviceName]['params']);
    }
}
