<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

$container = new Container();
$container->setArgumentBuilder(new ArgumentBuilder());
$container->loadServices([
    'now' => [
        'class' => 'DateTime',
    ],
    'ciao' => [
        'class' => 'DummyService',
        'params' => [
            '@now', // service
            'foo' => 'bar', // scalar
            42, // scalar
        ]
    ],
]);

$now = $container->get('ciao');

echo "\n" . $now->getNow()->format('Y-m-d'); // 2017-04-29
echo "\n" . $now->getFoo();                  // bar
echo "\n" . $now->getCiao();                 // 42
