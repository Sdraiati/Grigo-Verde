<?php
require_once 'model.php';

class Spazio extends Model {
    private $table = "SPAZIO";

    public function __construct(Database $db) {
        parent::__construct($db);
    }

    public function nuovo($posizione, $nome, $descrizione, $tipo, $n_tavoli) {
        $query = "INSERT INTO " . $this->table . " (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (?, ?, ?, ?, ?)";
        $params = [
            ['type' => 'i', 'value' => $posizione],
            ['type' => 's', 'value' => $nome],
            ['type' => 's', 'value' => $descrizione],
            ['type' => 's', 'value' => $tipo],
            ['type' => 'i', 'value' => $n_tavoli]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($posizione, $nome, $descrizione, $tipo, $n_tavoli) {
        $query = "UPDATE " . $this->table . " SET Nome = ?, Descrizione = ?, Tipo = ?, N_tavoli = ? WHERE Posizione = ?";
        $params = [
            ['type' => 's', 'value' => $nome],
            ['type' => 's', 'value' => $descrizione],
            ['type' => 's', 'value' => $tipo],
            ['type' => 'i', 'value' => $n_tavoli],
            ['type' => 'i', 'value' => $posizione]
        ];

        return $this->exec($query, $params);
    }

    public function elimina($posizione) {
        $query = "DELETE FROM " . $this->table . " WHERE Posizione = ?";
        $params = [
            ['type' => 'i', 'value' => $posizione]
        ];

        return $this->exec($query, $params);
    }
}