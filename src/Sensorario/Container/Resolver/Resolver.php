<?php

namespace Sensorario\Container\Resolver;

use Sensorario\Container\Objects\Argument;
use Sensorario\Container\Objects\Service;

class Resolver implements
    \Sensorario\Container\Resolver\ResolverInterface
{
    private $construcrtorResolver;

    public function setConstructorResolver(ConstructorResolver $resolver) : void
    {
        $this->construcrtorResolver = $resolver;
    }

    public function resolve(Service $service)
    {
        $resolution = $this->construcrtorResolver->resolve($service);

        foreach ($service->getMethods() as $methodName => $value) {
            $argument = Argument::fromString($value);

            if ($argument->isService()) {
                $collabortor = Service::box([
                    'name' => $argument->getServiceName(),
                    'services' => $service->getServicesConfiguration(),
                ]);

                $resolution->$methodName(
                    $this->construcrtorResolver->resolve($collabortor)
                );
            } else {
                $resolution->$methodName($value);
            }
        }

        return $resolution;
    }
}
