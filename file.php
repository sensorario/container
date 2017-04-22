<?php

class Tre
{
    public function __construct(
        Servizio $servizio,
        Due $due
    ) {
    }
}

class Servizio
{
    private $due;

    public function __construct(Due $due)
    {
        $this->due = $due;
    }
}

class Due
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
        'class' => 'Servizio',
        'params' => [
            'servizio.due'
        ]
    ],
    'servizio.due' => [
        'class' => 'Due',
    ],
    'servizio.tre' => [
        'class' => 'Tre',
        'params' => [
            'servizio',
            'servizio.due'
        ]
    ],
]);

$servizio = $container->get('servizio');

if (!($servizio instanceof Servizio)) {
    throw new \RuntimeException(
        'Oops!'
    );
}

$due = $container->get('servizio.due');

if (!($due instanceof Due)) {
    throw new \RuntimeException(
        'Oops! Non sono Due'
    );
}

$tre = $container->get('servizio.tre');

if (!($tre instanceof Tre)) {
    throw new \RuntimeException(
        'Oops! Non sono Due'
    );
}
