<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
class Login extends Endpoint
{
    public string $username = '';
    public string $password = '';
    public string $error = '';
    public function __construct()
    {
        parent::__construct('login', 'POST');
    }

    public function validate() : bool
    {
        $username = $this->sanitizeInput($this->post('username'));
        $password = $this->sanitizeInput($this->post('password'));

        if (empty($username) || empty($password)) {
            return false;
        }

        return true;
    }

    private function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }


    public function handle() : void
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if(!$this->validate())
        {
            $this->precompila($this->post('username'), $this->post('password'), 'Inserire username e password');
            header("Location: /login");
            exit();
        }
        else if (Autenticazione::login($username, $password)) {
            echo "Pagina utente";
            //header("Location: /");
            exit();
        } else {
            $this->precompila($username, $password, 'Username o password errati');
            header("Location: /login");
            exit();
        }
    }

    public function precompila($username, $password, $error) : void
    {
        $this->username = $username;
        $this->password = $password;
        $this->error = $error;
    }
}
