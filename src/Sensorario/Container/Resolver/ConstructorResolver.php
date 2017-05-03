<?php

namespace Sensorario\Container\Resolver;

use Sensorario\Container\Objects\Service;

class ConstructorResolver
{
    public function resolve(Service $service)
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
}
