# Container loader

I suggest you to create a ContainerLoader class like the following:

```php
use Sensorario\Container\Container;

class ContainerLoader
{
    public static function load() : Sensorario\Container\Container
    {
        $container =  new Container();

        $container->loadServices([
            'service.name' => [
                'class' => 'Your\Own\Service',
            ]
        ]);

        return $container;
    }
}
```

This allow you to load the container just with `ContainerLoader::load();`.

Just adding these lines:

```php
$this->container = ContainerLoader::load();
```

you'll reach all your services and components.
