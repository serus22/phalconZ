<?php

namespace PhalconZ;

use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;

class ZModule {

    private $__di;

    public function __construct() {
        //var_dump(debug_backtrace());die;
        //DiInterface $di;
        //$this->__di = $di;
    }

    public function di() {
        return $this->__di;
    }

    public function registerAutoloaders() {}

    public function registerServices(DiInterface $di) {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace( $this->modulename() . '\\Controllers\\');
            return $dispatcher;
        });
    }

    public function modulename() {
        return substr(get_called_class(), 0, strpos(get_called_class(), '\\'));
    }
}