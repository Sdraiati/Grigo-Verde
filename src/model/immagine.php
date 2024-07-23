<?php
require_once 'model.php';

class Immagine extends Model
{
    private $table = "IMMAGINE";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($img, $alt, $mime_type, $posizione_spazio)
    {
        $query = "INSERT INTO " . $this->table . " (Byte, Alt, Mime_type, Spazio) VALUES (?, ?, ?, ?)";
        $params = [
            ['type' => 'b', 'value' => $img],  // 'b' for blob
            ['type' => 's', 'value' => $alt],
            ['type' => 's', 'value' => $mime_type],
            ['type' => 'i', 'value' => $posizione_spazio]
        ];

        return $this->exec($query, $params);
    }

    public function modifica($id, $img, $alt, $mime_type, $posizione_spazio)
    {
        $query = "UPDATE " . $this->table . " SET Byte = ?, Alt = ?, Mime_type = ?, Spazio = ? WHERE Id = ?";
        $params = [
            ['type' => 'b', 'value' => $img],  // 'b' for blob
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