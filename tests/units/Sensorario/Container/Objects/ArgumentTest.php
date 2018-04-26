<?php

use Sensorario\Container\Objects\Argument;

class ArgumentTest extends  PHPUnit\Framework\TestCase
{
    public function testCanBeDefinedAsString()
    {
        $argument = Argument::fromString('foo');

        $this->assertEquals(
            'foo',
            $argument->getServiceName()
        );
    }

    public function testStringIsNotAService()
    {
        $argument = Argument::fromString('foo');

        $this->assertEquals(
            false,
            $argument->isService()
        );
    }

    public function testCanBeDefinedAsServiceWithAt()
    {
        $argument = Argument::fromString('@foo');

        $this->assertEquals(
            true,
            $argument->isService()
        );
    }

    public function testProvideServiceNameThrowGetter()
    {
        $argument = Argument::fromString('@foo');

        $this->assertEquals(
            'foo',
            $argument->getServiceName()
        );
    }
}
