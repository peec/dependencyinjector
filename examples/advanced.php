<?php
require "../vendor/autoload.php";

use \Pkj\DependencyInjector\DependencyInjector;


$di = new DependencyInjector();

// Static analysis is ON, now we dont have to give arguments twice...
$di->config(DependencyInjector::CONF_STATIC_ANALYSIS, true);


$di->service('test', function () {
    return "This is a test service...";
});


// As you can see below, we automatically know that $test refers to the "@test" service.
$di->service('test.something', function ($test) {
    return "Hello .... $test";
});


echo $di->service('test.something');




