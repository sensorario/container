<?php

namespace Sensorario\Container\Resolver;

use Sensorario\Container\Objects\Service;
use Sensorario\Container\Objects\Argument;

class Resolver
{
    private $construcrtorResolver;

    public function setConstructorResolver(ConstructorResolver $resolver)
    {
        $this->construcrtorResolver = $resolver;
    }

    public function resolve(Service $service)
    {
        $resolution = $this->construcrtorResolver->resolve($service);

        foreach ($service->getMethods() as $methodName => $value) {
            $argument = Argument::fromString($value);

            if ($argument->isService()) {
                $collabortor = Service::box(array(
                    'name' => $argument->getServiceName(),
                    'services' => $service->getServicesConfiguration(),
                ));

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
