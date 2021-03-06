<?php
namespace Pkj\DependencyInjector;

/**
 * Class DependencyInjector
 *
 * Simple yet powerful PHP Dependency injection container.
 *
 *
 * Usage:
 * ==========
 *
 * <code>
 * $di = new DependencyInjector();
 *
 * $di->service('world', function ($string) {
 *     return $string;
 * }, ['World']);
 *
 * $di->service('test', function ($number, $thing) {
 *     return "Hello $thing, this is a number: $number!";
 * }, [1337, '@world']); // Depends on service world
 *
 *
 * echo $di->service('test'); Returns the compiled output of the 'test' service.
 * </code>
 *
 *
 *
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 */
class DependencyInjector {

    private $services;
    private $build;
    private $config = array();

    const CONF_STATIC_ANALYSIS = 1;



    public function __construct () {
        $this->services = [];
        $this->build = [];
        $this->config = [
            self::CONF_STATIC_ANALYSIS => false
        ];
    }

    public function config($key, $value = null) {
        if ($value === null) {
            return $this->config[$key];
        } else {
            $this->config[$key] = $value;
        }
    }

    private function set($service, callable $callee, array $arguments = []) {
        if ($this->config(self::CONF_STATIC_ANALYSIS)) {
            if (!$arguments) {
                $arguments = $this->buildCallableArguments($callee);
            }
        }
        $this->services[$service] = [$callee, $arguments];
    }


    private function buildCallableArguments (callable $callee) {
        $args = array();
        $ref = new \ReflectionFunction($callee);
        foreach ($ref->getParameters() as $param) {
            $args[] = '@'.$param->getName();
        }
        return $args;
    }

    private function isBuilt($service) {
        return isset($this->build[$service]);
    }

    private function build($service) {
        if (!isset($this->services[$service])) {
            throw new \Exception("Service '$service' does not exist, ");
        }
        list($callee, $arguments) = $this->services[$service];
        $this->build[$service] = call_user_func_array($callee, $this->buildArguments($arguments));
        return $this->build[$service];
    }

    private function buildArguments (array $arguments) {
        foreach ($arguments as $k => $val) {
            // Is a service:
            if (is_string($val) && substr($val, 0, 1) === '@') {
                $arguments[$k] = $this->build(substr($val, 1));
            } else {
                $arguments[$k] = $val;
            }
        }
        return $arguments;
    }

    public function service($service, $callee = null, $arguments = []) {
        if ($callee !== null) {
            return $this->set($service, $callee, $arguments);
        } else {
            if (!isset($this->services[$service])) {
                throw new \Exception("Service '$service' does not exist, ");
            }
            if (!$this->isBuilt($service)) {
                $this->build($service);
            }
            return $this->build[$service];
        }
    }

}
