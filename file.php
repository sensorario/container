<?php

class FooService
{
    public function __construct(
        BarService $servizio,
        EmptyService $due
    ) {
    }
}

class BarService
{
    private $due;

    public function __construct(EmptyService $due)
    {
        $this->due = $due;
    }
}

class EmptyService
{
}

class Container
{
    private $services = [];

    public function loadServices($services)
    {
        $this->services = $services;
    }

    public function get($serviceName)
    {
        $className = $this->services[$serviceName]['class'];

        $arguments = [];
        if (isset($this->services[$serviceName]['params'])) {
            $params = $this->services[$serviceName]['params'];

            if (count($params) == 1) {
                return new $className($this->get($params[0]));
            }

            if (count($params) == 2) {
                return new $className(
                    $this->get($params[0]),
                    $this->get($params[1])
                );
            }
        }

        return new $className($arguments);
    }
}

$container = new Container();

$container->loadServices([
    'servizio' => [
        'class' => 'BarService',
        'params' => [
            'servizio.due'
        ]
    ],
    'servizio.due' => [
        'class' => 'EmptyService',
    ],
    'servizio.tre' => [
        'class' => 'FooService',
        'params' => [
            'servizio',
            'servizio.due'
        ]
    ],
]);

$servizio = $container->get('servizio');

if (!($servizio instanceof BarService)) {
    throw new \RuntimeException(
        'Oops!'
    );
}

$due = $container->get('servizio.due');

if (!($due instanceof EmptyService)) {
    throw new \RuntimeException(
        'Oops! Non sono EmptyService'
    );
}

$tre = $container->get('servizio.tre');

if (!($tre instanceof FooService)) {
    throw new \RuntimeException(
        'Oops! Non sono EmptyService'
    );
}
