<?php

namespace PhalconZ\Lib;

use Phalcon\Di;

abstract class AbstractViewHelper {

  private $_di;

  public function __construct(Di $di) {
    $this->_di = $di;
  }

  /**
   * @return Di
   */
  public function di() {
    return $this->_di;
  }

  public abstract function __invoke($args = null);
}