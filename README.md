# PHP - Dependency Injection Container

Simple.


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

