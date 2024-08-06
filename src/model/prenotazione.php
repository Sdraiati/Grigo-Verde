<?php
require_once 'model.php';
require_once 'disponibilità.php';

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

    public function modifica($dataInizioOld, $dataFineOld, $usernameOld, $spazioOld, $dataInizio, $dataFine, $username, $spazio, $descrizione)
    {
        $query = "UPDATE " . $this->table . " 
        SET DataInizio = ?, DataFine = ?, Username = ?, Spazio = ?, Descrizione = ? 
        WHERE DataInizio = ? AND DataFine = ? AND Username = ? AND Spazio = ?";

        $params = [
            ['type' => 's', 'value' => $dataInizio],
            ['type' => 's', 'value' => $dataFine],
            ['type' => 's', 'value' => $username],
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $descrizione],
            ['type' => 's', 'value' => $dataInizioOld],
            ['type' => 's', 'value' => $dataFineOld],
            ['type' => 's', 'value' => $usernameOld],
            ['type' => 'i', 'value' => $spazioOld]
        ];

        return $this->exec($query, $params);
    }

    public function elimina($dataInizio, $dataFine, $username, $spazio)
    {
        $query = "DELETE FROM " . $this->table . " WHERE DataInizio = ? AND DataFine = ? AND Username = ? AND Spazio = ?";
        $params = [
            ['type' => 's', 'value' => $dataInizio], // 's' per DATETIME
            ['type' => 's', 'value' => $dataFine],   // 's' per DATETIME
            ['type' => 's', 'value' => $username],    // 's' per VARCHAR
            ['type' => 'i', 'value' => $spazio]       // 'i' per INT
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

    public function is_available($spazio, $begin_time, $end_time)
    {
        // check if there is no other booking in the same time
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND ((DataInizio < ? AND DataFine > ?) OR (DataInizio < ? AND DataFine > ?))";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $end_time]
        ];

        $res = $this->get_all($query, $params);
        return count($res) == 0;
    }

    public function user_already_booked($username, $begin_time, $end_time)
    {
        // check if user has no other booking in the same time
        $query = "SELECT * FROM " . $this->table . " WHERE Username = ? AND ((DataInizio < ? AND DataFine > ?) OR (DataInizio < ? AND DataFine > ?))";
        $params = [
            ['type' => 's', 'value' => $username],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $end_time]
        ];

        $res = $this->get_all($query, $params);
        return count($res) == 0;
    }

    public function prendi_by_id($res_id)
    {
        $query = "SELECT DataInizio, DataFine, UTENTE.Nome, UTENTE.Cognome, SPAZIO.Nome AS NomeSpazio, PRENOTAZIONE.Descrizione FROM " . $this->table . " 
                    JOIN SPAZIO ON PRENOTAZIONE.Spazio = SPAZIO.Posizione
                    JOIN UTENTE ON PRENOTAZIONE.Username = UTENTE.Username
                    WHERE PRENOTAZIONE.Id = ?";
        $params = [
            ['type' => 'i', 'value' => $res_id]
        ];

        return $this->get($query, $params);
    }
}
