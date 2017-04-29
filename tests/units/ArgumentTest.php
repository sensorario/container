<?php

use PHPUnit\Framework\TestCase;
use Sensorario\Container\Objects\Argument;

class ArgumentTest extends TestCase
{
    public function test()
    {
        $argument = Argument::fromString('foo');
        $this->assertEquals(
            'foo',
            $argument->getServiceName()
        );
    }

    public function testBar()
    {
        $argument = Argument::fromString('foo');
        $this->assertEquals(
            false,
            $argument->containsChiocciola()
        );
    }

    public function testFooBar()
    {
        $argument = Argument::fromString('@foo');
        $this->assertEquals(
            true,
            $argument->containsChiocciola()
        );
    }

    public function testFoo()
    {
        $argument = Argument::fromString('@foo');
        $this->assertEquals(
            'foo',
            $argument->getServiceName()
        );
    }
}
