## Usage

```php
use Sensorario\Container\Container;
use Sensorario\Container\ArgumentBuilder;

class Hello
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
        'class' => 'Hello',
        'params' => [
            '@now',
        ]
    ],
]);

$now = $container->get('ciao');

$now->getNow();
```
