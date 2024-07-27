<?php
require_once 'model.php';

class Disponibilita extends Model
{
    private $table = "DISPONIBILITA";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($spazio, $mese, $giorno_settimana, $orario_apertura, $orario_chiusura)
    {
        $query = "INSERT INTO " . $this->table . " (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (?, ?, ?, ?, ?)";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $mese],
            ['type' => 's', 'value' => $giorno_settimana],
            ['type' => 's', 'value' => $orario_apertura],
            ['type' => 's', 'value' => $orario_chiusura]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($spazio, $mese, $giorno_settimana, $orario_apertura, $orario_chiusura)
    {
        $query = "UPDATE " . $this->table . " SET Mese = ?, Giorno_settimana = ?, Orario_apertura = ?, Orario_chiusura = ? WHERE Spazio = ?";
        $params = [
            ['type' => 's', 'value' => $mese],
            ['type' => 's', 'value' => $giorno_settimana],
            ['type' => 's', 'value' => $orario_apertura],
            ['type' => 's', 'value' => $orario_chiusura],
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->exec($query, $params);
    }

    public function elimina($spazio, $mese, $giorno_settimana)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Spazio = ? AND Mese = ? AND Giorno_settimana = ?";
        $params = [
            ['type' => 'i', 'value' => $spazio],
            ['type' => 's', 'value' => $mese],
            ['type' => 's', 'value' => $giorno_settimana]
        ];

        return $this->exec($query, $params);
    }

    public function prendi_per_giorno($spazio)
    {
        $query = "SELECT * FROM " . $this->table . " 
              WHERE Spazio = ? 
              AND CURDATE() <= DATE_ADD(CONCAT(YEAR(CURDATE()), '-', MONTH(CURDATE()), '-', DAY(CURDATE())), INTERVAL 14 DAY)";
        $params = [
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->get_all($query, $params);
    }

    public function prendi($spazio)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ? AND CURDATE() <= NOW()";
        $params = [
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->get_all($query, $params);
    }
}