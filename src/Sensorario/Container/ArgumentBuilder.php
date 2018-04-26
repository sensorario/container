<?php

namespace Sensorario\Container;

use Psr\Container\ContainerInterface;

class ArgumentBuilder
{
    private $container;

    private $params = [];

    public function setContainer(ContainerInterface $container) : void
    {
        $this->container = $container;
    }

    public function setParams(array $params) : void
    {
        $this->params = $params;
    }

    public function getArguments() : array
    {
        $arguments = [];

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
