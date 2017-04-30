<?php

namespace Sensorario\Container\Objects;

class Argument
{
    private $name;

    private function __construct($name)
    {
        $this->name = $name;
    }

    public static function fromString($name)
    {
        return new self($name);
    }

    public function isService()
    {
        return 0 === strpos($this->name, '@');
    }

    public function getServiceName()
    {
        if ($this->isService()) {
            return str_replace('@', '', $this->name);
        }

        return $this->name;
    }
}
