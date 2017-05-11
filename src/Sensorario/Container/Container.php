<?php

namespace Sensorario\Container;

use Sensorario\Container\Objects\Argument;
use Sensorario\Container\Objects\Service;
use Sensorario\Container\Resolver\ConstructorResolver;
use Sensorario\Container\Resolver\MethodResolver;
use Sensorario\Container\Resolver\Resolver;

class Container
{
    private $services = [];

    private $builder;

    private $resolver;

    private $construcrtorResolver;

    private $methodResolver;

    private $register;

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

    public function loadServices(array $services) : void
    {
        $this->services = $services;
    }

    public function getServicesConfiguration() : array
    {
        return $this->services;
    }

    private function ensureServiceIsDefined(string $serviceName) : bool
    {
        if (!$this->contains($serviceName)) {
            throw new \RuntimeException(
                'Oops! Service ' . $serviceName . ' not defined'
            );
        }

        return true;
    }

    public function get(string $serviceName)
    {
        $this->ensureServiceIsDefined($serviceName);

        $service = Service::box([
            'name' => $serviceName,
            'services' => $this->services,
        ]);

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

    public function contains(string $serviceName) : bool
    {
        $service = Argument::fromString($serviceName);
        return isset($this->services[$service->getServiceName()]);
    }

    public function hasArguments(string $serviceName) : bool
    {
        return isset($this->services[$serviceName]['params']);
    }
}
