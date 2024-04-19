<?php

class B {}

class A {
    public function __construct(B $b) {}
    public function welcome() {
        return "welcome";
    }
}

class ServiceContainer {
    protected static $container = []; // array to store values

    public function bind($name, $instance) {
        self::$container[$name] = $instance;
    }

    public function make($name) {
        return self::$container[$name];
    }
}

$b = new B();
$a = new A($b);

$sc = new ServiceContainer();
$sc->bind('class_a', $a);

class facadeA {
    protected static function resolveFacadeInstance($name) {
        return (new ServiceContainer)->make($name);
    }

    public static function __callStatic($method, $args) {
        $instance = static::resolveFacadeInstance(static::getFacadeAccessor());
        return $instance->$method(...$args);
    }

    protected static function getFacadeAccessor() {
        return 'class_a';
    }
}

echo facadeA::welcome(); // Output: welcome
