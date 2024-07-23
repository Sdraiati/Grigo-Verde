<?php
require_once 'model.php';

class Utente extends Model
{
    private $table = "UTENTE";

    public function nuovo($username, $nome, $cognome, $ruolo, $password)
    {
        $query = "INSERT INTO " . $this->table . " (Username, Nome, Cognome, Ruolo, Password) VALUES (?, ?, ?, ?, ?)";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [
            ['type' => 's', 'value' => $username],
            ['type' => 's', 'value' => $nome],
            ['type' => 's', 'value' => $cognome],
            ['type' => 's', 'value' => $ruolo],
            ['type' => 's', 'value' => $hashed_password]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($username, $nome, $cognome, $ruolo, $password)
    {
        $query = "UPDATE " . $this->table . " SET Nome = ?, Cognome = ?, Ruolo = ?, Password = ? WHERE Username = ?";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [
            ['type' => 's', 'value' => $nome],
            ['type' => 's', 'value' => $cognome],
            ['type' => 's', 'value' => $ruolo],
            ['type' => 's', 'value' => $hashed_password],
            ['type' => 's', 'value' => $username]
        ];

        return $this->exec($query, $params);
    }

    public function elimina($username)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Username = ?";
        $params = [
            ['type' => 's', 'value' => $username]
        ];

        return $this->exec($query, $params);
    }
}

