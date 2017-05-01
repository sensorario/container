## Method Injection

To inject dependencies via methods, specify what method injectors ad value in `methods` configuration. Method injectors can inject both scalar values and services.

```php
use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

$container = new Container();
$container->setArgumentBuilder(new ArgumentBuilder());
$container->loadServices([
    'now' => [
        'class' => 'DateTime',
    ],
    'ciao' => [
        'class' => 'DummyMethodService',
        'methods' => [
            'setFoo' => '@foo',
            'setScalar' => '42',
        ]
    ],
]);

$now = $container->get('ciao');

echo "\n" . $metodo->getFoo()->format('Y-m-d'); // 2017-04-29
echo "\n" . $metodo->getScalar();               // 42
```

## Links

 - [DummyMethodService][1]'s class

 [1]: ../tests/resources/DummyMethodService.php
