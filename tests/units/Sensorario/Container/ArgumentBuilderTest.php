<?php

use Sensorario\Container\ArgumentBuilder;

class ArgumentBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! Container is not defined
     */
    public function testCantBuildArgumentsWithoutContainer()
    {
        $builder = new ArgumentBuilder();
        $arguments = $builder->getArguments();
    }

    public function testCreateEmptyCollectionOfCollaboratorsByDefault()
    {
        $this->container = $this
            ->getMockBuilder('Sensorario\Container\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $builder = new ArgumentBuilder();
        $builder->setContainer($this->container);
        $arguments = $builder->getArguments();

        $this->assertEquals(array(), $arguments);
    }

    public function test()
    {
        $this->container = $this
            ->getMockBuilder('Sensorario\Container\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fooClass = $this
            ->getMockBuilder('Foo\Bar')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->once())
            ->method('get')
            ->with('@foo')
            ->will($this->returnValue($this->fooClass));

        $builder = new ArgumentBuilder();
        $builder->setParams(array('@foo'));
        $builder->setContainer($this->container);

        $this->assertEquals(
            array($this->fooClass),
            $builder->getArguments()
        );
    }
}
