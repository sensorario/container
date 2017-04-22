<?php

class Servizio
{
}

class Due
{
}

class Container
{
    public function get($serviceName)
    {
        $services = [];

        $services['servizio'] = 'Servizio';
        $services['servizio.due'] = 'Due';

        $className = $services[$serviceName];

        return new $className();
    }
}

$container = new Container();

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
