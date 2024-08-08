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

    public function validate(): bool
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if (empty($username) || empty($password)) {
            return false;
        }

        $this->username = $username;
        $this->password = $password;
        return true;
    }

    public function containSpace($input): bool
    {
        return str_contains($input, ' ');
    }


    public function handle(): void
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if (!$this->validate()) {
            $page = new LoginPage($this->username, $this->password, "Inserire username e password");
            echo $page->render();
        } elseif ($this->containSpace($username) || $this->containSpace($password)) {
            $page = new LoginPage($this->username, $this->password, "Username e password non possono contenere spazi");
            echo $page->render();
        } elseif (Autenticazione::login($username, $password)) {
            $_SESSION['message'] = "Benvenuto $username!";
            echo "Pagina utente";
            // $this->redirect('dashboard')
        } else {
            $page = new LoginPage($this->username, $this->password, "Username o password errati");
            $page->setPath("login");
            echo $page->render();
        }
    }
}
