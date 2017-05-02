<?php

namespace Sensorario\Container;

class ConstructorResolver
{
    public function resolve(Objects\Service $service)
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
