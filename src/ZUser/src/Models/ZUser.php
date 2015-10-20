<?php

namespace PhalconZ\ZUser\Models;

use JsonSerializable;
use PhalconZ\Rest\Models\SmartCollection;

class ZUser extends SmartCollection implements JsonSerializable {

    public function getSource() {
        return 'user';
    }

    public function jsonSerialize() {
        $r = parent::toArray();
        unset($r['password'], $r['salt']);
        return $r;
    }
}