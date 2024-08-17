<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'model/utente.php';
require_once $project_root . '/page/unauthorized.php';
require_once $project_root . '/page/resource_not_found.php';

class editUserPage extends Page
{
    private string $username = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';
    private string $error = '';

    public function __construct(string $username = '', string $nome = '', string $cognome = '', string $ruolo = '', string $error = '')
    {
        parent::__construct();
        $this->setTitle('Modifica Utente');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Utenti' => 'utenti',
            'Utenti' => 'utenti/utente?username=' . $username,
        ]);
        $this->addKeywords(["modifica utente, amministratore, docente"]);

        $this->username = $username;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->ruolo = $ruolo;
        $this->error = $error;
    }

    public function fetch(): array
    {
        if (isset($_GET['username'])) {
            $this->username = $_GET['username'];
        } else {
            $page = new ResourceNotFoundPage();
            $page->setPath($this->path);
            return [false, $page->render()];
        }

        if (
            $this->username !== '' && $this->nome === '' && $this->cognome === '' && $this->ruolo === ''
            && $this->error === ''
        ) {
            $utente = new Utente();

            if ($utente->prendi($this->username) !== null) {
                $result = $utente->prendi($this->username);
                $this->nome = $result['Nome'];
                $this->cognome = $result['Cognome'];
                $this->ruolo = $result['Ruolo'];
            } else {
                $page = new ResourceNotFoundPage();
                $page->setPath($this->path);
                return [false, $page->render()];
            }
        }
        return [true, ''];
    }

    public function render(): string
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            return $page->render();
        }
        if (!Autenticazione::is_amministratore()) {
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            return $page->render();
        }

        $res = $this->fetch();
        if (!$res[0]) {
            return $res[1];
        }

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('new_user'), $content);

        $content = str_replace("{{ action }}", $this->path, $content);
        $content = str_replace("{{ operazione }}", "Modifica", $content);
        $content = str_replace("{{ normal_input }}", '', $content);
        $content = str_replace(
            "{{ hidden_input }}",
            '<input type="hidden" id="username_hidden" name="username" value="{{ username }}">',
            $content
        );
        $content = str_replace(
            "{{ hide-password-fields }}",
            'class=\'hidden\' disabled',
            $content
        );
        $content = str_replace(
            "{{ edit_password_button }}",
            '<input type="button" id="edit_password_button" value="Modifica password" 
                    onclick="showEditPassword()">',
            $content
        );
        $content = str_replace("{{ password-required }}", '', $content);

        if ($this->nome != '') {
            $content = str_replace("{{ nome }}", $this->nome, $content);
            $content = str_replace("{{ cognome }}", $this->cognome, $content);
            $content = str_replace("{{ username }}", $this->username, $content);

            $content = str_replace("{{ selectedDefault }}", '', $content);
            $content = str_replace("{{ selectedAmministratore }}", $this->ruolo == 'Amministratore' ? 'selected' : '', $content);
            $content = str_replace("{{ selectedDocente }}", $this->ruolo == 'Docente' ? 'selected' : '', $content);

            if ($this->error != '') {
                $content = str_replace("{{ error }}", $this->error($this->error), $content);
            } else {
                $content = str_replace("{{ error }}", '', $content);
            }
        } else {
            $content = str_replace("{{ nome }}", '', $content);
            $content = str_replace("{{ cognome }}", '', $content);
            $content = str_replace("{{ username }}", '', $content);
            $content = str_replace("{{ selectedDefault }}", 'selected', $content);
            $content = str_replace("{{ selectedAmministratore }}", '', $content);
            $content = str_replace("{{ selectedDocente }}", '', $content);
            $content = str_replace("{{ error }}", '', $content);
        }
        return $content;
    }
}
