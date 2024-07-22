<?php
require_once 'model.php';

class Utente extends Model {
    private $table = "UTENTE";
    public function __construct(Database $db) {
        parent::__construct($db);
    }

    private function execQuery($query, $types, $params) {
        $stmt = $this->db->conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        try{
            $stmt->execute();
            return true;
        }catch(Exception $e)
        {
            return false;
        }
    }

    public function creaUtente($username, $nome, $cognome, $ruolo, $password) {
        $query = "INSERT INTO " . $this->table . " (Username, Nome, Cognome, Ruolo, Password) VALUES (?, ?, ?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [$username, $nome, $cognome, $ruolo, $hashed_password];
        
        return $this->execQuery($query, "sssss", $params);
    }

    public function modificaUtente($username, $nome, $cognome, $ruolo, $password) {
        $query = "UPDATE " . $this->table . " SET Nome = ?, Cognome = ?, Ruolo = ?, Password = ? WHERE Username = ?";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [$nome, $cognome, $ruolo, $hashed_password, $username];

        return $this->execQuery($query, "sssss", $params);
    }

    public function eliminaUtente($username) {
        $query = "DELETE FROM " . $this->table . " WHERE Username = ?";
        $params = [$username];

        return $this->execQuery($query, "s", $params);
    }
}