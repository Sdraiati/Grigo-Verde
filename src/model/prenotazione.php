<?php
require_once 'model.php';

class Prenotazione extends Model
{
    private $table = "PRENOTAZIONE";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($dataInizio, $dataFine, $username, $spazio, $descrizione)
    {
        $query = "INSERT INTO " . $this->table . " (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES (?, ?, ?, ?, ?)";
        $params = [
            ['type' => 's', 'value' => $dataInizio],
            ['type' => 's', 'value' => $dataFine],
            ['type' => 's', 'value' => $username],
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $descrizione]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($id, $dataInizio, $dataFine, $username, $spazio, $descrizione)
    {
        $query = "UPDATE " . $this->table . " SET DataInizio = ?, DataFine = ?, Username = ?, Spazio = ?, Descrizione = ? WHERE Id = ?";
        $params = [
            ['type' => 's', 'value' => $dataInizio],
            ['type' => 's', 'value' => $dataFine],
            ['type' => 's', 'value' => $username],
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $descrizione],
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

        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND DataFine >= ? AND DataInizio < ?";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $day],
            ['type' => 's', 'value' => $endDate]
        ];

        return $this->get_all($query, $params);
    }
}
