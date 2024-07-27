<?php
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/database.php';
class Autenticazione
{
    public static function login($username, $password) : bool
    {
        $db = Database::getInstance();
        $sql = "SELECT password FROM UTENTE WHERE username = ? AND password = ?";
        $params = [
            ['type' => 's', 'value' => $username],
            ['type' => 's', 'value' => $password],
        ];
        $stmt = $db->bindParams($sql, $params);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }
    public static function logout()
    {
        session_destroy();
    }
    public static function isLogged()
    {
        return isset($_SESSION['username']);
    }
    public static function getLoggedUser()
    {
        return $_SESSION['username'];
    }
}