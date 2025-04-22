<?php
require_once 'BaseModel.php';

class Contacts extends BaseModel {
    public function __construct() {
        parent::__construct('contacts');
    }
}