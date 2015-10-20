<?php

namespace PhalconZ\Rest\Controllers;

use Exception;

class RestException extends Exception {

    public function __construct($code) {
        parent::__construct(BaseController::httpMessage($code), $code);
    }
}