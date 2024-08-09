<?php
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/database.php';
include_once $project_root . '/model/utente.php';
class Autenticazione
{
    private static function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public static function session_by_cookie(): void
    {
        self::ensureSessionStarted();
        $logged = isset($_COOKIE["LogIn"]);
        if ($logged) {
            $_SESSION["username"] = $_COOKIE["LogIn"];
            session_write_close();
        }
    }
    public static function login($username, $password): bool
    {
        self::ensureSessionStarted();
        //        $db = Database::getInstance();
        //        $sql = "SELECT password FROM UTENTE WHERE username = ? AND password = ?";
        //        $params = [
        //            ['type' => 's', 'value' => $username],
        //            ['type' => 's', 'value' => $password],
        //        ];
        //        $stmt = $db->bindParams($sql, $params);
        //        $stmt->execute();
        //        $result = $stmt->fetch();
        $utente = new Utente();
        $result = $utente->check_password($username, $password);
        if ($result) {
            $_SESSION['username'] = $username;
            setcookie("LogIn", $username, time() + (86400 * 30), "/"); // Set a cookie for 30 days
            session_write_close();
            return true;
        }
        return false;
    }
    public static function logout(): void
    {
        self::ensureSessionStarted();
        session_destroy();

        $params = session_get_cookie_params();
        // remove session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // remove LogIn cookie
        if (isset($_COOKIE['LogIn'])) {
            setcookie(
                'LogIn',
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        $_SESSION = [];
    }
    public static function isLogged(): bool
    {
        self::ensureSessionStarted();
        return isset($_SESSION['username']);
    }
    public static function getLoggedUser()
    {
        self::ensureSessionStarted();
        return $_SESSION['username'];
    }

    public static function is_amministratore(): bool
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM UTENTE WHERE RUOLO = 'amministratore' AND username = ?";
        $params = [
            ['type' => 's', 'value' => self::getLoggedUser()],
        ];
        $stmt = $db->bindParams($sql, $params);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            return true;
        }
        return false;
    }
}

