<?php

namespace Sensorario\Container;

class ArgumentBuilder
{
    private $container;

    private $params = array();

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getArguments()
    {
        $arguments = array();

        if (!$this->container) {
            throw new \RuntimeException(
                'Oops! Container is not defined'
            );
        }

        foreach ($this->params as $param) {
            $parameter = Objects\Argument::fromString($param);

            $arguments[] = $parameter->isService()
                ? $this->container->get($param)
                : $param;
        }

        return $arguments;
    }
}
