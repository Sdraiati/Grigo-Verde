<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
class Login extends Endpoint
{
    public string $username = '';
    public string $password = '';
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
        $this->username = $username;
        $this->password = $password;
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
            $page = new LoginPage($this->username, $this->password, "Inserire username e password");
            echo $page->render();
        }
        else if (Autenticazione::login($username, $password)) {
            echo "Pagina utente";
            //header("Location: /");
        } else {
            $page = new LoginPage($this->username, $this->password, "Username o password errati");
            echo $page->render();
        }
    }
}
