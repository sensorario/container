<?php

use PHPUnit\Framework\TestCase;
use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Container;

class ContainerTest extends TestCase
{
    public function testCanBeDefinedWithZeroServices()
    {
        $container = new Container();
        $container->loadServices([]);
        $this->assertEquals(
            [],
            $container->getServicesConfiguration()
        );
    }

    public function testHasMethodToCheckIfHasService()
    {
        $container = new Container();
        $container->loadServices([]);

        $this->assertSame(
            false,
            $container->contains('service-name')
        );
    }

    public function testCheckIfAServiceConstructorHasArguments()
    {
        $container = new Container();
        $container->loadServices([]);

        $this->assertSame(
            false,
            $container->hasArguments('service-name')
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! Service foo not defined
     */
    public function testThrowAnExceptionWhenRequestedServiceIsNotDefined()
    {
        $container = new Container();
        $container->get('foo');
    }

    public function testProvideServiceInstance()
    {
        $container = new Container();
        $container->loadServices([
            'service' => [
                'class' => 'Foo\Bar',
            ]
        ]);
        $this->assertEquals(
            'Foo\Bar',
            get_class($container->get('service'))
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! No builder, no party
     */
    public function testCantBuildConstructorWithoutBuilder()
    {
        $container = new Container();
        $container->loadServices([
            'service' => [
                'class' => 'Foo\Bar',
                'params' => [
                    'DateTime',
                    'DateInterval'
                ]
            ]
        ]);
        $this->assertEquals(
            'Foo\Bar',
            get_class($container->get('service'))
        );
    }

    public function testBuildServicesWithCollaboratorsAndScalarTypes()
    {
        $container = new Container();
        $container->setArgumentBuilder(new ArgumentBuilder());
        $container->loadServices([
            'foo' => [
                'class' => 'DateTime',
            ],
            'service' => [
                'class' => 'Resources\Ciaone',
                'params' => [
                    '@foo',
                    'foo' => 'bar',
                    42
                ]
            ]
        ]);

        $this->assertEquals(
            'Resources\Ciaone',
            get_class($container->get('service'))
        );
    }

    public function testOnlyInstance()
    {
        $container = new Container();
        $container->setArgumentBuilder(new ArgumentBuilder());
        $container->loadServices([
            'foo' => [
                'class' => 'DateTime',
            ],
            'service' => [
                'class' => 'Resources\Ciaone',
                'params' => [
                    '@foo',
                    'foo' => 'bar',
                    42
                ]
            ]
        ]);

        $firstCall = $container->get('service');
        $secondCall = $container->get('service');

        $this->assertSame(
            $firstCall,
            $secondCall
        );
    }
}
