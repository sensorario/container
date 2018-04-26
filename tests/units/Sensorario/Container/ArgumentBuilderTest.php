<?php

use Sensorario\Container\ArgumentBuilder;

class ArgumentBuilderTest extends PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->container = $this
            ->getMockBuilder('Psr\Container\ContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->builder = new ArgumentBuilder();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! Container is not defined
     */
    public function testCantBuildArgumentsWithoutContainer()
    {
        $arguments = $this->builder->getArguments();
    }

    public function testCreateEmptyCollectionOfCollaboratorsByDefault()
    {
        $this->builder->setContainer($this->container);
        $arguments = $this->builder->getArguments();

        $this->assertEquals(array(), $arguments);
    }

    public function testProvideCollectionOfArguments()
    {
        $this->serviceClass = $this
            ->getMockBuilder('Foo\Bar')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->once())
            ->method('get')
            ->with('@foo')
            ->will($this->returnValue($this->serviceClass));

        $this->builder->setParams(array('@foo'));
        $this->builder->setContainer($this->container);

        $this->assertEquals(
            array($this->serviceClass),
            $this->builder->getArguments()
        );
    }
}
