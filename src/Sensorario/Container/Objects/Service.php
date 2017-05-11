<?php

namespace Sensorario\Container\Objects;

class Service
{
    private $params;

    private function __construct(array $params)
    {
        $this->params = $params;
    }

    public static function box(array $params) : self
    {
        return new self($params);
    }

    public function hasParams() : bool
    {
        return isset($this->params['services'][$this->getName()]['params']);
    }

    public function getParams() : array
    {
        return $this->hasParams()
            ? $this->params['services'][$this->getName()]['params']
            : [];
    }

    public function hasMethods() : bool
    {
        return isset($this->params['services'][$this->getName()]['methods']);
    }

    public function getMethods() : array
    {
        return $this->hasMethods()
            ? $this->params['services'][$this->getName()]['methods']
            : [];
    }

    public function getClass() : string
    {
        $argument = Argument::fromString($this->params['name']);
        $serviceName = $argument->getServiceName();
        return $this->params['services'][$serviceName]['class'];
    }

    public function getServicesConfiguration() : array
    {
        return $this->params['services'];
    }

    public function getName() : string
    {
        return $this->params['name'];
    }

    public function hasMethodInjection() : bool
    {
        return [] == $this->getMethods()
            && [] != $this->getParams();
    }

    public function hasSimpleInjection() : bool
    {
        return [] != $this->getMethods()
            && [] == $this->getParams();
    }

    public function classNotExists() : bool
    {
        return !class_exists($this->getClass());
    }
}
