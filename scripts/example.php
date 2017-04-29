<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

class DummyService
{
    private $now;

    private $foo;

    private $ciao;

    public function __construct(
        \DateTime $now,
        string $foo,
        int $ciao
    ) {
        $this->now = $now;
        $this->foo = $foo;
        $this->ciao = $ciao;
    }

    public function getNow()
    {
        return $this->now;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function getCiao()
    {
        return $this->ciao;
    }
}

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

var_dump($now);
var_dump($now->getNow());
var_dump($now->getFoo());
var_dump($now->getCiao());
