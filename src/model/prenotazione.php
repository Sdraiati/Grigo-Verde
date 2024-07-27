<?php
require_once 'model.php';

class Prenotazione extends Model
{
    private $table = "PRENOTAZIONE";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($data, $username, $spazio)
    {
        $query = "INSERT INTO " . $this->table . " (Data, Username, Spazio) VALUES (?, ?, ?)";
        $params = [
            ['type' => 's', 'value' => $data],
            ['type' => 's', 'value' => $username],
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($id, $data, $username, $spazio)
    {
        $query = "UPDATE " . $this->table . " SET Data = ?, Username = ?, Spazio = ? WHERE Id = ?";
        $params = [
            ['type' => 's', 'value' => $data],
            ['type' => 's', 'value' => $username],
            ['type' => 'i', 'value' => $spazio],
            ['type' => 'i', 'value' => $id]
        ];

        return $this->exec($query, $params);
    }

    public function elimina($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Id = ?";
        $params = [
            ['type' => 'i', 'value' => $id]
        ];

        return $this->exec($query, $params);
    }

    public function prendi($spazio)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ?";
        $params = [
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->get_all($query, $params);
    }

    public function prendi_per_settimana($spazio, $day)
    {
        $endDate = date('Y-m-d H:i:s', strtotime($day . ' +1 week'));

        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND Data >= ? AND Data < ?";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $day],
            ['type' => 's', 'value' => $endDate]
        ];

        return $this->get_all($query, $params);
    }
}