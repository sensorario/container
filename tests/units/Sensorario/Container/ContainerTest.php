<?php

use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Container;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeDefinedWithZeroServices()
    {
        $container = new Container();
        $container->loadServices(array());
        $this->assertEquals(
            array(),
            $container->getServicesConfiguration()
        );
    }

    public function testHasMethodToCheckIfHasService()
    {
        $container = new Container();
        $container->loadServices(array());

        $this->assertSame(
            false,
            $container->contains('service-name')
        );
    }

    public function testCheckIfAServiceConstructorHasArguments()
    {
        $container = new Container();
        $container->loadServices(array());

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

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegexp Oops! Service .* not defined as .*
     */
    public function testServiceWithWrongConfiguration()
    {
        $container = new Container();

        $container->loadServices(array(
            'service' => array(
                'class' => 'Not\Existent\Class',
            )
        ));

        $container->get('service');
    }

    public function testProvideServiceInstance()
    {
        $container = new Container();
        $container->loadServices(array(
            'service' => array(
                'class' => 'Foo\Bar',
            )
        ));
        $this->assertEquals(
            'Foo\Bar',
            get_class($container->get('service'))
        );
    }

    public function testBuildServicesWithCollaboratorsAndScalarTypes()
    {
        $container = new Container();
        $container->loadServices(array(
            'foo' => array(
                'class' => 'DateTime',
            ),
            'service' => array(
                'class' => 'Resources\Ciaone',
                'params' => array(
                    '@foo',
                    'foo' => 'bar',
                    42
                )
            )
        ));

        $this->assertEquals(
            'Resources\Ciaone',
            get_class($container->get('service'))
        );
    }

    public function testOnlyInstance()
    {
        $container = new Container();
        $container->loadServices(array(
            'foo' => array(
                'class' => 'DateTime',
            ),
            'service' => array(
                'class' => 'Resources\Ciaone',
                'params' => array(
                    '@foo',
                    'foo' => 'bar',
                    42
                )
            )
        ));

        $firstCall = $container->get('service');
        $secondCall = $container->get('service');

        $this->assertSame(
            $firstCall,
            $secondCall
        );
    }

    public function testBuildServicesViaMethodInjection()
    {
        $container = new Container();
        $container->loadServices(array(
            'foo' => array(
                'class' => 'DateTime',
            ),
            'service' => array(
                'class' => 'DummyMethodService',
                'methods' => array(
                    'setFoo' => '@foo',
                )
            )
        ));

        $service = $container->get('service');
    }
}
