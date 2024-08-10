<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
require_once 'message.php';

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

    public function render_login_page_with_error($error)
    {
        $page = new LoginPage($this->username, $this->password, $error);
        $page->setPath("login");
        echo $page->render();
    }

    public function handle(): void
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if (!$this->validate()) {
            $this->render_login_page_with_error("Inserire username e password");
            return;
        } elseif ($this->containSpace($username) || $this->containSpace($password)) {
            $this->render_login_page_with_error("Username e password non possono contenere spazi");
            return;
        } elseif (!Autenticazione::login($username, $password)) {
            $this->render_login_page_with_error("Username o password errati");
            return;
        }
        Message::set("Login effettuato con successo");
        $this->redirect('dashboard');
    }
}
