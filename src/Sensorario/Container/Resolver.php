<?php

namespace Sensorario\Container;

use ReflectionClass;

class Resolver
{
    private $instances = array();

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

    public function simpleResolver($service)
    {
        if ($service->classNotExists()) {
            throw new \RuntimeException(
                'Oops! Class ' . $service->getClass() .
                ' defined as ' . $service->getName() .
                ' not found!!!'
            );
        }

        $serviceClass = $service->getClass();

        return new $serviceClass();
    }

    public function methods($service)
    {
        $resolution = $this->simpleResolver($service);

        foreach ($service->getMethods() as $methodName => $value) {
            $argument = Objects\Argument::fromString($value);

            if ($argument->isService()) {
                $collabortor = Objects\Service::box(array(
                    'name' => $argument->getServiceName(),
                    'services' => $service->getServicesConfiguration(),
                ));

                $resolution->$methodName(
                    $this->simpleResolver($collabortor)
                );
            } else {
                $resolution->$methodName($value);
            }
        }

        return $resolution;
    }
}
