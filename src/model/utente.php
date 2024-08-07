<?php
require_once 'model.php';

class Utente extends Model
{
    private $table = "UTENTE";

    public function __construct()
    {
        parent::__construct();
    }

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

    public function modifica($username, $nome, $cognome, $ruolo, $password='')
    {
        if($password === '')
        {
            $query = "UPDATE " . $this->table . " SET Nome = ?, Cognome = ?, Ruolo = ? WHERE Username = ?";
            $params = [
                ['type' => 's', 'value' => $nome],
                ['type' => 's', 'value' => $cognome],
                ['type' => 's', 'value' => $ruolo],
                ['type' => 's', 'value' => $username]
            ];
            return $this->exec($query, $params);
        }
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

    public function modifica_password($username, $password)
    {
        $query = "UPDATE " . $this->table . " SET Password = ? WHERE Username = ?";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $params = [
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

    public function prendi($username)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Username = ?";
        $params = [
            ['type' => 's', 'value' => $username]
        ];

        return $this->get($query, $params);
    }

    public function check_password($username, $password)
    {
        $query = "SELECT Password FROM " . $this->table . " WHERE Username = ?";
        $params = [
            ['type' => 's', 'value' => $username]
        ];

        $result = $this->get($query, $params);
        if ($result === null) {
            return false;
        }
        return password_verify($password, $result['Password']);
    }
}
