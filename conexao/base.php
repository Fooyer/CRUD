<?php

include_once 'db.php';
class Base extends DB {
    public function getParam($paramName) {
        return isset($_REQUEST[$paramName]) ? $_REQUEST[$paramName] : null;
    }
}