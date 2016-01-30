<?php

namespace PhalconZ\ZUser\Filters;

use Zend\InputFilter\InputFilter;
use Zend\Validator\Hostname;

class ZUserFilter extends InputFilter {

  public function __construct() {
    $this->add([
      'name' => '_id',
      'required' => false,
      'filters' => [
        ['name' => 'PhalconZ\\Rest\\Filters\\MongoIdStringFilter']
      ],
      'validators' => [
        ['name' => 'PhalconZ\\Rest\\Validators\\MongoIdStringValidator']
      ]
    ]);

    $this->add([
      'name' => 'username',
      'required' => true,
      'filters' => [
        ['name' => 'StringTrim']
      ]
    ]);

    $this->add([
      'name' => 'email',
      'required' => true,
      'filters' => [
        ['name' => 'StringTrim']
      ],
      'validators' => [
        [
          'name' => 'Zend\\Validator\\EmailAddress',
          'options' => [
            'allow' => Hostname::ALLOW_DNS,
            'useMxCheck'    => true,
          ],
        ],
      ],
    ]);

    $this->add([
      'name' => 'password',
      'required' => true,
      'filters' => [
        ['name' => 'StringTrim']
      ]
    ]);
  }
}