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
        return isset($this->params['services'][$this->getName()]['params'])
            ? $this->params['services'][$this->getName()]['params']
            : array();
    }

    public function getMethods()
    {
        return isset($this->params['services'][$this->getName()]['methods'])
            ? $this->params['services'][$this->getName()]['methods']
            : array();
    }

    public function getClass()
    {
        $argument = Argument::fromString($this->params['name']);
        $serviceName = $argument->getServiceName();
        return $this->params['services'][$serviceName]['class'];
    }

    public function getServicesConfiguration()
    {
        return $this->params['services'];
    }

    public function getName()
    {
        return $this->params['name'];
    }

    public function isConstructorEmpty()
    {
        return array() == $this->getParams();
    }

    public function isConstructorInjection()
    {
        return array() == $this->getMethods()
            && array() != $this->getParams();
    }

    public function isMethodInjection()
    {
        return array() != $this->getMethods()
            && array() == $this->getParams();
    }

    public function classNotExists()
    {
        return !class_exists($this->getClass());
    }
}
