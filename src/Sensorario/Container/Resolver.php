<?php

namespace Sensorario\Container;

class Resolver
{
    private $construcrtorResolver;

    private $methodResolver;

    public function setConstructorResolver(ConstructorResolver $resolver)
    {
        $this->construcrtorResolver = $resolver;
    }

    public function setMethodResolver(MethodResolver $resolver)
    {
        $this->methodResolver = $resolver;
    }

    public function methods($service)
    {
        $resolution = $this->construcrtorResolver->resolve($service);

        foreach ($service->getMethods() as $methodName => $value) {
            $argument = Objects\Argument::fromString($value);

            if ($argument->isService()) {
                $collabortor = Objects\Service::box(array(
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
