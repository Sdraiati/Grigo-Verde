<?php

$project_root = dirname(__FILE__, 2);
require_once $project_root . '/global_values.php';
class Database
{
    public $conn;
    private static $instance = null;
    private function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance == null) {
            try {
                self::$instance = new Database();
            } catch (Exception $e) {
                die("get instance error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public function bindParams(string $sql, array $params): mysqli_stmt | false
    {
        $stmt = $this->conn->prepare($sql);

        $types = "";
        $values = [];
        foreach ($params as $param) {
            // bind_param requires the type in a string
            $types .= $param['type'];
            $values[] = $param['value'];
        }

        if (strlen($types)) {
            //... is the spread operator, it allows to pass an array as a list of arguments
            if (!$stmt->bind_param($types, ...$values)) {
                error_log("Binding parameters failed: " . $stmt->error);
                return false;
            }
        }
        return $stmt;
    }
}