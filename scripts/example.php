<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

class Ciao
{
    private $now;

    public function __construct(
        \DateTime $now
    ) {
        $this->now = $now;
    }

    public function getNow()
    {
        return $this->now;
    }
}

$container = new Container();
$container->setArgumentBuilder(new ArgumentBuilder());
$container->loadServices([
    'now' => [
        'class' => 'DateTime',
    ],
    'ciao' => [
        'class' => 'Ciao',
        'params' => [
            'now',
        ]
    ],
    // 'servizio.due' => [
    //     'class' => 'EmptyService',
    // ],
    // 'servizio.tre' => [
    //     'class' => 'FooService',
    //     'params' => [
    //         'servizio',
    //         'servizio.due'
    //     ]
    // ],
]);

$now = $container->get('ciao');

var_dump($now);
var_dump($now->getNow());
