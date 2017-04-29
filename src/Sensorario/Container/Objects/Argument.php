<?php

namespace Sensorario\Container\Objects;

class Argument
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name)
    {
        return new self($name);
    }

    public function isService()
    {
        return 0 === strpos($this->name, '@');
    }

    public function isScalar()
    {
        return false;
    }

    public function getServiceName()
    {
        if ($this->isService()) {
            return str_replace('@', '', $this->name);
        }

        return $this->name;
    }
}
