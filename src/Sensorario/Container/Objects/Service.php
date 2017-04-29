<?php

namespace Sensorario\Container\Objects;

class Service
{
    private $params;

    private function __construct(array $params)
    {
        $this->params = $params;
    }

    public static function box(array $params)
    {
        return new self($params);
    }

    public function getParams()
    {
        return $this->params['services'][$this->getName()]['params'] ?? [];
    }

    public function getClass()
    {
        $argument = Argument::fromString($this->params['name']);
        $serviceName = $argument->getServiceName();
        return $this->params['services'][$serviceName]['class'];
    }

    public function getName()
    {
        return $this->params['name'];
    }

    public function isConstructorEmpy()
    {
        return [] == $this->getParams();
    }

    public function classNotExists()
    {
        return !class_exists($this->getClass());
    }
}
