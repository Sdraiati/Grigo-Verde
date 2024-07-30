<?php
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/database.php';
include_once $project_root . '/model/spazio.php';
class Spazio {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        // utilizzare il metodo prendi tutti dal model Spazio.
        $model_spazio = new Spazio(); 
        $model_spazio->prendi_tutti();
    }

    public function get_by($tipo, $data) {
        if ($tipo == null && $data == null) {
            http_response_code(400);
            echo 'Error Missing parameters: ' . $tipo . ' ' . $data;
        }
        
        // caso in cui almeno uno degli input sia apposto
        $db = Database::getInstance();
        $sql = "SELECT * FROM SPAZIO WHERE tipo = ? AND disponibilità = ?";
        $params = [
            ['type' => 's', 'value' => $tipo],
            ['type' => 'd', 'value' => $data], // non sono proprio sicuro che d stia type cate
        ];
        $stmt = $db->bindParams($sql, $params);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            return "sono trovati degli spazi corrispondenti ai parametri indicati"
        }
        return "non sono stati trovati degli spazi";
        
    }
}