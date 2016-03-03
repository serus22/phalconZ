<?php

namespace PhalconZ\Lib;

use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Di\InjectionAwareInterface;

abstract class AbstractControllerHelper implements InjectionAwareInterface {

  private $_di;

  public function __construct(Di $di) {
    $this->_di = $di;
  }

  /**
   * @return Di
   */
  public function getDi() {
    return $this->_di;
  }

  /**
   * @param DiInterface
   * @return AbstractViewHelper
   */
  public function setDi(DiInterface $dependencyInjector) {
    $this->_di = $dependencyInjector;

  }

  public abstract function __invoke($args = null);
}