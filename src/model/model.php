<?php
require_once 'database.php';

abstract class Model
{
    private Database $db;

    protected function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function exec($query, $params)
    {
        $stmt = $this->db->bindParams($query, $params);

        if ($stmt === false) {
            return false;
        }
        
        try {
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function get($query, $params)
    { {
            $stmt = $this->db->bindParams($query, $params);

            if ($stmt === false) {
                return false;
            }

            try {
                $stmt->execute();
                return $stmt->get_result()->fetch_assoc();
            } catch (Exception $e) {
                return false;
            }
        }
    }

    protected function get_all($query, $params)
    { {
            $stmt = $this->db->bindParams($query, $params);

            if ($stmt === false) {
                return false;
            }

            try {
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            } catch (Exception $e) {
                return false;
            }
        }
    }

}
