# PHP - Dependency Injection Container

Simple dependency injection container (DIC) or otherwise known as Inversion of Control (IoC). DIC helps you sort out dependencies for objects in a nice manner.


## Example


```php


$di = new DependencyInjector();


class World {
    private $str;

    public function __construct($str) {
        $this->str = $str;
    }

    public function __toString () {
        return $this->str;
    }
}


$di->service('world', function ($string) {
    return new World($string);
}, ['World']);





$di->service('test', function ($number, $thing) {
    return "Hello $thing, this is a number: $number!";
}, [1337, '@world']);


echo $di->service('test');



```

Outputs:

```bash
Hello World, this is a number: 1337!
```


## Documentation


### Services

Use the `service` method to create new services.

- *Argument 1:* The name of the service
- *Argument 2:* A callable that should return the value of the service when we want to fetch your service. Can return anything from a primitive value to a complex object.
- *Argument 3:* Values to pass into the `Argument 2`'s arguments. Use a string starting with `@` to inject a service, otherwise you can inject anything. Note that everything starting with a `@` is treated as a service.


### Configuration

It's possible to configure the `DependencyInjector` with the `config` method. You can also define custom global settings using `->config($key, $value)`.

Listed configuration below:

#### DependencyInjector::CONF_STATIC_ANALYSIS

For convenience you can set `DependencyInjector::CONF_STATIC_ANALYSIS` to true, by doing this you don't need to specify arguments in the third argument using the `service` method.

Example:

```php
// Enable static anlysis using reflection.
$di->config(DependencyInjector::CONF_STATIC_ANALYSIS, true);

// The following code:


$di->service('test', function ($world) {
    return "Hello $world!";
}, ['@world']);


// Can now become:

$di->service('test', function ($world) {
    return "Hello $world!";
});


```