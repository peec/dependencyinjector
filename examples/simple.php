<?php
require "../vendor/autoload.php";

use \Pkj\DependencyInjector\DependencyInjector;


$di = new DependencyInjector();



$di->service('test.something', function () {
    return "Hello you.";
});


echo $di->service('test.something');

