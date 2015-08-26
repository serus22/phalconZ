<?php

namespace PhalconZ\Rest\Validators;

use Zend\Validator\AbstractValidator;
use Exception;

class MongoDocumentExistsValidator extends AbstractValidator {

    private $valid = true;

    private $val;
    private $class;

    public function isValid($value) {
        $this->val = $value;
        $options = $this->getOptions();
        if(! isSet($options['collection'])) throw new Exception('Collection doesnt specified');
        $this->class = @$options['collection'];
        $this->valid = !! @forward_static_call($this->class . '::findById', new \MongoId($value));
        return $this->valid;
    }

    public function getMessages() {
        return $this->valid ? [] : ["Document $this->class for ID: $this->val not found"];
    }
}