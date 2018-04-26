<?php

use Sensorario\Container\ArgumentBuilder;
use Sensorario\Container\Container;

class ContainerTest extends PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->container = new Container();
    }

    public function testCanBeDefinedWithZeroServices()
    {
        $this->container->loadServices(array());
        $this->assertEquals(
            array(),
            $this->container->getServicesConfiguration()
        );
    }

    public function testHasMethodToCheckIfHasService()
    {
        $this->container->loadServices(array());

        $this->assertSame(
            false,
            $this->container->contains('service-name')
        );
    }

    public function testCheckIfAServiceConstructorHasArguments()
    {
        $this->container->loadServices(array());

        $this->assertSame(
            false,
            $this->container->hasArguments('service-name')
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! Service foo not defined
     */
    public function testThrowAnExceptionWhenRequestedServiceIsNotDefined()
    {
        $this->container->get('foo');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegexp Oops! Service .* not defined as .*
     */
    public function testServiceWithWrongConfiguration()
    {
        $this->container->loadServices(array(
            'service' => array(
                'class' => 'Not\Existent\Class',
            )
        ));

        $this->container->get('service');
    }

    public function testProvideServiceInstance()
    {
        $this->container->loadServices(array(
            'service' => array(
                'class' => 'Foo\Foo\Bar',
            )
        ));

        $this->assertEquals(
            'Foo\Foo\Bar',
            get_class($this->container->get('service'))
        );
    }

    public function testBuildServicesWithCollaboratorsAndScalarTypes()
    {
        $this->container->loadServices(array(
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
            get_class($this->container->get('service'))
        );
    }

    public function testOnlyInstance()
    {
        $this->container->loadServices(array(
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

        $firstCall = $this->container->get('service');
        $secondCall = $this->container->get('service');

        $this->assertSame(
            $firstCall,
            $secondCall
        );
    }

    public function testBuildServicesViaMethodInjection()
    {
        $this->container = new Container();
        $this->container->loadServices(array(
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

        $service = $this->container->get('service');

        $this->assertInstanceOf(DummyMethodService::class, $service);
    }

    public function testBuildServicesViaMethodInjectionSame()
    {
        $this->container = new Container();
        $this->container->loadServices(array(
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

        $service = $this->container->get('service');
        $service2 = $this->container->get('service');

        $this->assertSame(
            $service,
            $service2
        );
    }
}
