<?php

namespace Sensorario\Container\Objects;

class Argument
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name) : self
    {
        return new self($name);
    }

    public function isService() : bool
    {
        return 0 === strpos($this->name, '@');
    }

    public function getServiceName() : string
    {
        if ($this->isService()) {
            return str_replace('@', '', $this->name);
        }

        return $this->name;
    }
}
