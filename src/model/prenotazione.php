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

    public function modifica($id, $dataInizio, $dataFine, $spazio, $descrizione)
    {
        $query = "UPDATE " . $this->table . " 
        SET DataInizio = ?, DataFine = ?, Spazio = ?, Descrizione = ? 
        WHERE Id = ?";

        $params = [
            ['type' => 's', 'value' => $dataInizio],
            ['type' => 's', 'value' => $dataFine],
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
            ['type' => 'i', 'value' => $id]       // 'i' per INT
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

    public function prendi_per_intervallo($dataInizio, $dataFine) 
    {
        // dataInizio e dataFine devono essere nel formato: YYYY-MM-DD HH:SS:MM
        $query = 'SELECT * FROM PRENOTAZIONE WHERE DataInizio >= ? AND DataFine <= ? ORDER BY PRENOTAZIONE.Spazio';
        $params = [
            ['type' => 's', 'value' => $dataInizio],
            ['type' => 's', 'value' => $dataFine],
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

    public function prendi_per_utente($username, $day = NULL)
    {
        if ($day == NULL) {
            $query = "SELECT * FROM " . $this->table . " WHERE Username = ?";
            $params = [
                ['type' => 's', 'value' => $username]
            ];
        } else {
            $query = "SELECT * FROM " . $this->table . " WHERE Username = ? AND DataFine >= ?";
            $params = [
                ['type' => 's', 'value' => $username],
                ['type' => 's', 'value' => $day]
            ];
        }

        return $this->get_all($query, $params);
    }

    public function is_available($spazio, $begin_time, $end_time)
    {
        // check if there is no other booking in the same time
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND ((DataInizio <= ? AND DataFine > ?) OR (DataInizio < ? AND DataFine >= ?) OR (DataInizio > ? AND DataFine < ?))";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time]
        ];

        $res = $this->get_all($query, $params);
        return count($res) == 0;
    }

    public function user_already_booked($username, $begin_time, $end_time)
    {
        // check if user has no other booking in the same time
        $query = "SELECT * FROM " . $this->table . " WHERE Username = ? AND ((DataInizio <= ? AND DataFine > ?) OR (DataInizio < ? AND DataFine >= ?) OR (DataInizio > ? AND DataFine < ?))";
        $params = [
            ['type' => 's', 'value' => $username],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $end_time],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time]
        ];

        $res = $this->get_all($query, $params);
        return count($res) == 0;
    }

    public function prendi_by_id($res_id)
    {
        $query = "SELECT DataInizio, DataFine, U.Nome, U.Cognome, S.Nome AS NomeSpazio, PRENOTAZIONE.Descrizione, PRENOTAZIONE.Spazio, U.Username FROM " . $this->table . " 
                    JOIN SPAZIO AS S ON PRENOTAZIONE.Spazio = S.Posizione
                    JOIN UTENTE AS U ON PRENOTAZIONE.Username = U.Username
                    WHERE PRENOTAZIONE.Id = ?";
        $params = [
            ['type' => 'i', 'value' => $res_id]
        ];

        return $this->get($query, $params);
    }

    public function prendi_by($begin_time, $end_time, $space)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND DataInizio = ? AND DataFine = ?";
        $params = [
            ['type' => 'i', 'value' => $space],
            ['type' => 's', 'value' => $begin_time],
            ['type' => 's', 'value' => $end_time]
        ];

        return $this->get($query, $params);
    }

    public function prendi_by_user($username)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Username = ? AND DataInizio > CURRENT_TIME";
        $params = [
            ['type' => 's', 'value' => $username],
        ];

        return $this->get_all($query, $params);
    }

    public function prendi_all()
    {
        $query = "SELECT 
                DataInizio, DataFine, P.Descrizione, P.Id,
                    U.Nome, U.Cognome, U.Username,
                    S.Nome AS NomeSpazio, S.Posizione 
                FROM " . $this->table . "  AS P
                JOIN SPAZIO AS S ON P.Spazio = S.Posizione
                JOIN UTENTE AS U ON P.Username = U.Username
                WHERE P.DataFine >= ?
                ORDER BY P.DataFine ASC";

        date_default_timezone_set('UTC');
        $currentDateTime = date('Y/m/d H:i:s');
        $params = [
            ['type' => 's', 'value' => $currentDateTime]
        ];

        return $this->get_all($query, $params);
    }
}
