<?php

class DummyMethodService
{
    private $foo;

    private $scalar;

    public function getFoo()
    {
        return $this->foo;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    public function setScalar($scalar)
    {
        $this->scalar = $scalar;
    }

    public function getScalar()
    {
        return $this->scalar;
    }
}
