<?php

require_once 'db_config.php';
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;
    private static $instance = null;
    private function __construct($host, $db_name, $username, $password) {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
        $this->conn = new mysqli($host, $username, $password, $db_name);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance($host, $db_name, $username, $password) : Database {
        if (self::$instance == null) {
            try {
                self::$instance = new Database($host, $db_name, $username, $password);
            } catch (Exception $e) {
                die("get instance error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}