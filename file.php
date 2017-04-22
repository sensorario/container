<?php

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
            return new $className($this->get($params[0]));
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
