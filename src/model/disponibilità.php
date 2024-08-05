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
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ?";
        $params = [
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->get_all($query, $params);
    }

    public function is_open($spazio, $day, $begin_time, $end_time)
    {
        $aperture = $this->prendi_per_giorno($spazio);

        $mese = get_month($day);
        $giorno_settimana = get_weekday($day);
        // return $begin_time;
        foreach ($aperture as $apertura) {
            if ($apertura['Mese'] == $mese && $apertura['Giorno_settimana'] == $giorno_settimana) {
                $orario_apertura = $apertura['Orario_apertura'];
                $orario_chiusura = $apertura['Orario_chiusura'];

                if ($begin_time >= $orario_apertura && $end_time <= $orario_chiusura) {
                    return true;
                }
            }
        }
        return false;
    }
}

function get_month($date)
{
    $date = new DateTime($date);
    $month = intval($date->format('m'));
    switch ($month) {
        case 1:
            return 'Gennaio';
        case 2:
            return 'Febbraio';
        case 3:
            return 'Marzo';
        case 4:
            return 'Aprile';
        case 5:
            return 'Maggio';
        case 6:
            return 'Giugno';
        case 7:
            return 'Luglio';
        case 8:
            return 'Agosto';
        case 9:
            return 'Settembre';
        case 10:
            return 'Ottobre';
        case 11:
            return 'Novembre';
        case 12:
            return 'Dicembre';
    }
}

function get_weekday($date)
{
    $date = new DateTime($date);
    $weekDay = $date->format('w');
    switch ($weekDay) {
        case 0:
            return 'Domenica';
        case 1:
            return 'Lunedì';
        case 2:
            return 'Martedì';
        case 3:
            return 'Mercoledì';
        case 4:
            return 'Giovedì';
        case 5:
            return 'Venerdì';
        case 6:
            return 'Sabato';
    }
}
