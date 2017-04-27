<?php

use PHPUnit\Framework\TestCase;
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

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Oops! Service not defined
     */
    public function testThrowAnExceptionWhenRequestedServiceIsNotDefined()
    {
        $container = new Container();
        $container->get('foo');
    }

    public function testFooooooo()
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
    public function testBaaaaaaaar()
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
}
