<?php

class DummyService
{
    private $now;

    private $foo;

    private $ciao;

    public function __construct(
        \DateTime $now,
        $foo,
        $ciao
    ) {
        $this->now = $now;
        $this->foo = $foo;
        $this->ciao = $ciao;
    }

    public function getNow()
    {
        return $this->now;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function getCiao()
    {
        return $this->ciao;
    }
}

