<?php

use PHPUnit\Framework\TestCase;
use Sensorario\Container\ArgumentBuilder;

class ArgumentBuilderTest extends TestCase
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

        $this->assertEquals([], $arguments);
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
            ->with('Foo\Bar')
            ->will($this->returnValue($this->fooClass));

        $builder = new ArgumentBuilder();
        $builder->setParams(['Foo\Bar']);
        $builder->setContainer($this->container);

        $this->assertEquals(
            [$this->fooClass],
            $builder->getArguments()
        );
    }
}
