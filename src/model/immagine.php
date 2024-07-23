<?php
require_once 'model.php';

class Immagine extends Model
{
    private $table = "IMMAGINE";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($dato, $alt, $mime_type, $posizione_spazio)
    {
        $query = "INSERT INTO " . $this->table . " (Dato, Alt, Mime_type, Posizione_spazio) VALUES (?, ?, ?, ?)";
        $params = [
            ['type' => 'b', 'value' => $dato],  // 'b' for blob
            ['type' => 's', 'value' => $alt],
            ['type' => 's', 'value' => $mime_type],
            ['type' => 'i', 'value' => $posizione_spazio]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($id, $dato, $alt, $mime_type, $posizione_spazio)
    {
        $query = "UPDATE " . $this->table . " SET Dato = ?, Alt = ?, Mime_type = ?, Posizione_spazio = ? WHERE Id = ?";
        $params = [
            ['type' => 'b', 'value' => $dato],  // 'b' for blob
            ['type' => 's', 'value' => $alt],
            ['type' => 's', 'value' => $mime_type],
            ['type' => 'i', 'value' => $posizione_spazio],
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
}
