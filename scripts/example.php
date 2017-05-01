<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

$container = new Container();
$container->setArgumentBuilder(new ArgumentBuilder());
$container->loadServices(array(
    'now' => array(
        'class' => 'DateTime',
    ),
    'ciao' => array(
        'class' => 'DummyService',
        'params' => array(
            '@now', // service
            'foo' => 'bar', // scalar
            42, // scalar
        )
    ),
    'metodo' => array(
        'class' => 'DummyMethodService',
        'methods' => array(
            'setFoo' => '@now',
            'setScalar' => '42',
        )
    ),
));

$now = $container->get('ciao');

echo "\n" . $now->getNow()->format('Y-m-d'); // 2017-04-29
echo "\n" . $now->getFoo();                  // bar
echo "\n" . $now->getCiao();                 // 42

$metodo = $container->get('metodo');

echo "\n" . $metodo->getFoo()->format('Y-m-d'); // 2017-04-29
echo "\n" . $metodo->getScalar(); // 2017-04-29
