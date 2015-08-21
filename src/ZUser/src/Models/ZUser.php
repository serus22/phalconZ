<?php

namespace PhalconZ\ZUser\Models;

use PhalconZ\Rest\Models\SmartCollection;

class ZUser extends SmartCollection {

    public function getSource() {
        return 'user';
    }
}