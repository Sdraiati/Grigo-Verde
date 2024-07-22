<?php
require_once 'database.php';

class Model {
    protected Database $db;

    protected function __construct(Database $db) {
        $this->db = $db;
    }
}