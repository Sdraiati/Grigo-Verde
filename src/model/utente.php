<?php
require_once 'model.php';

class Utente extends Model
{
    private $table = "UTENTE";

    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    public function nuovo($username, $nome, $cognome, $ruolo, $password)
    {
        $query = "INSERT INTO " . $this->table . " (Username, Nome, Cognome, Ruolo, Password) VALUES (?, ?, ?, ?, ?)";

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [$username, $nome, $cognome, $ruolo, $hashed_password];

        return $this->exec($query, $params);
    }

    public function modifica($username, $nome, $cognome, $ruolo, $password)
    {
        $query = "UPDATE " . $this->table . " SET Nome = ?, Cognome = ?, Ruolo = ?, Password = ? WHERE Username = ?";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [$nome, $cognome, $ruolo, $hashed_password, $username];

        return $this->exec($query, $params);
    }

    public function elimina($username)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Username = ?";
        $params = [$username];

        return $this->exec($query, $params);
    }
}
