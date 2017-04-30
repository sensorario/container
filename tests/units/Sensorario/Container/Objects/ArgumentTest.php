<?php

use Sensorario\Container\Objects\Argument;

class ArgumentTest extends  PHPUnit_Framework_TestCase
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
            $argument->isService()
        );
    }

    public function testFooBar()
    {
        $argument = Argument::fromString('@foo');
        $this->assertEquals(
            true,
            $argument->isService()
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
