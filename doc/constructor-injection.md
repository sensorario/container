## Constructor Injection

To inject dependencies via constructor, add arguments to the constructor signature and specify what service or scalar value you want.

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
```

## Links

 - [DummyService][1]'s class

 [1]: ../tests/resources/DummyService.php
