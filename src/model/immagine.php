<?php
require_once 'model.php';

class Immagine extends Model
{
    private $table = "IMMAGINE";

    public function __construct()
    {
        parent::__construct();
    }

    public function nuovo($img, $alt, $mime_type, $spazio)
    {
        $query = "INSERT INTO " . $this->table . " (Byte, Alt, Mime_type, Spazio) VALUES (?, ?, ?, ?)";
        $params = [
            ['type' => 's', 'value' => $img],  // 'b' for blob
            ['type' => 's', 'value' => $alt],
            ['type' => 's', 'value' => $mime_type],
            ['type' => 'i', 'value' => $spazio]
        ];
        return $this->exec($query, $params);
    }

    public function modifica($spazio, $img, $alt, $mime_type)
    {
        $query = "UPDATE " . $this->table . " SET Byte = ?, Alt = ?, Mime_type = ? WHERE Spazio = ?";
        $params = [
            ['type' => 's', 'value' => $img],
            ['type' => 's', 'value' => $alt],
            ['type' => 's', 'value' => $mime_type],
            ['type' => 'i', 'value' => $spazio],
        ];

        return $this->exec($query, $params);
    }

    public function elimina($spazio)
    {
        $query = "DELETE FROM " . $this->table . " WHERE Spazio = ?";
        $params = [
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->exec($query, $params);
    }

    public function prendi($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE Spazio = ?";
        $params = [
            ['type' => 'i', 'value' => $id]
        ];

        $res = $this->get($query, $params);
        return $res;
    }

    public function modifica_descrizione($spazio, $descrizione)
    {
        $query = "UPDATE " . $this->table . " SET Alt = ? WHERE Spazio = ?";
        $params = [
            ['type' => 's', 'value' => $descrizione],
            ['type' => 'i', 'value' => $spazio]
        ];

        return $this->exec($query, $params);
    }
}
