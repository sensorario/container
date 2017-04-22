<?php

class Servizio
{
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
        $className = $this->services[$serviceName];

        return new $className();
    }
}

$container = new Container();

$container->loadServices([
    'servizio' => 'Servizio',
    'servizio.due' => 'Due',
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
