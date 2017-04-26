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

    private $builder;

    public function __construct(
        ArgumentBuilder $builder
    ) {
        $this->builder = $builder;

        $this->builder->setContainer($this);
    }

    public function loadServices($services)
    {
        $this->services = $services;
    }

    public function get($serviceName)
    {
        $serviceClass = $this->services[$serviceName]['class'];

        if (!isset($this->services[$serviceName]['params'])) {
            return new $serviceClass();
        }

        $params = $this->services[$serviceName]['params'];

        $arguments = $this->builder
            ->setParams($params)
            ->getArguments();

        return (new ReflectionClass($serviceClass))
            ->newInstanceArgs($arguments);
    }
}

class ArgumentBuilder
{
    private $container;

    private $params;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    public function getArguments()
    {
        $arguments = [];

        foreach ($this->params as $instanceParam) {
            $arguments[] = $this->container->get($instanceParam);
        }

        return $arguments;
    }
}

$container = new Container(
    new ArgumentBuilder()
);

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
