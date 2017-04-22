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
        if ($serviceName == 'servizio') {
            $className = 'Servizio';
        }

        if ($serviceName == 'servizio.due') {
            $className = 'Due';
        }

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
