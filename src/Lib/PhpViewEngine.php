<?php

namespace PhalconZ\Lib;

use Phalcon\Mvc\View\Engine\Php;

class PhpViewEngine extends Php {

  function __call($name, $args = null) {
    if(! $name || (! isSet($this->$name) && ! isSet($this->view->$name))) return;
    $a = isSet($this->$name) ? $this->$name : $this->view->$name;
    $list = class_implements($a);
    if($a instanceof AbstractViewHelper) return $a->__invoke($args);
    return $this;
  }
}