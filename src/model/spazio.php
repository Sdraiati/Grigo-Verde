<?php
require_once 'model.php';

class Spazio extends Model
{
    private $table = "SPAZIO";

    public function __construct()
    {
        parent::__construct();
    }

    public function exists($posizione) : bool
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Posizione = ?";
        $params = [
            ['type' => 'i', 'value' => $posizione]
        ];

        return $this->get($query, $params) !== null;
    }

    public function nuovo($posizione, $nome, $descrizione, $tipo, $n_tavoli)
    {
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

    public function modifica($posizione, $nome, $descrizione, $tipo, $n_tavoli)
    {
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

    public function elimina($posizione)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Posizione = ?";
        $params = [
            ['type' => 'i', 'value' => $posizione]
        ];

        return $this->exec($query, $params);
    }

    public function prendi($posizione)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Posizione = ?";
        $params = [
            ['type' => 'i', 'value' => $posizione]
        ];

        return $this->get($query, $params);
    }

    public function prendi_per_nome($nome)// : array | null
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Nome = ?";
        $params = [
            ['type' => 's', 'value' => $nome]
        ];

        return $this->get($query, $params);
    }

    public function prendi_tutti_con_immagini() 
    {
        $query = "SELECT * FROM SPAZIO LEFT JOIN IMMAGINE ON SPAZIO.Posizione = IMMAGINE.Spazio;";
        return $this->get_all($query, []);
    }

    public function prendi_tutti()
    {
        $query = "SELECT * FROM " . $this->table;
        return $this->get_all($query, []);
    }
}
