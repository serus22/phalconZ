<?php

namespace PhalconZ\ZUser\Filters;

use Zend\InputFilter\InputFilter;

class ZUserFilter extends InputFilter {

    public function __construct() {
        $this->add([
            'name' => '_id',
            'required' => true,
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
    }
}