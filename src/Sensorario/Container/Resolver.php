<?php

namespace Sensorario\Container;

use ReflectionClass;

class Resolver
{
    private $instances = array();

    private $construcrtorResolver;

    public function setConstructorResolver(ConstructorResolver $resolver)
    {
        $this->construcrtorResolver = $resolver;
    }

    public function resolve($service, ArgumentBuilder $builder)
    {
        $builder->setParams($service->getParams());

        $arguments = $builder->getArguments();

        if (!isset($this->instances[$service->getName()])) {
            $refObj = new ReflectionClass($service->getClass());
            $this->instances[$service->getName()] = $refObj->newInstanceArgs($arguments);
        }

        return $this->instances[$service->getName()];
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
