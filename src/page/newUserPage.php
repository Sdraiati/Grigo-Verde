<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'model/utente.php';
require_once $project_root . '/page/unauthorized.php';

class newUserPage extends Page
{
    private string $username = '';
    private string $password = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';
    private string $error = '';
    public function __construct(string $username = '', string $password = '', string $nome = '', string $cognome = '', string $ruolo = '', string $error = '')
    {
        parent::__construct();
        $this->setTitle('Nuovo Utente');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Utenti' => 'utenti'
        ]);
        $this->setPath('/utenti/nuovo');
        $this->addKeywords([""]);

        $this->username = $username;
        $this->password = $password;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->ruolo = $ruolo;
        $this->error = $error;
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

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('new_user'), $content);

        $content = str_replace("{{ action }}", $this->path, $content);
        $content = str_replace("{{ operazione }}", "Crea", $content);
        $content = str_replace(
            "{{ normal_input }}",
            '<label for="username">Nome utente <span class="required" aria-hidden="true">*</span></label>
            <input type="text" id="username" name="username" placeholder="Inserire un nome utente" 
            value="{{ username }}"',
            $content
        );
        $content = str_replace("{{ hidden_input }}", '', $content);
        $content = str_replace("{{ hide-password-fields }}", '', $content);
        $content = str_replace("{{ edit_password_button }}", '', $content);
        $content = str_replace("{{ password-required }}", 'required', $content);

        if ($this->nome != '') {
            $content = str_replace("{{ nome }}", $this->nome, $content);
            $content = str_replace("{{ cognome }}", $this->cognome, $content);
            $content = str_replace("{{ username }}", $this->username, $content);
            $content = str_replace("{{ password }}", $this->password, $content);

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
            $content = str_replace("{{ password }}", '', $content);
            $content = str_replace("{{ selectedDefault }}", 'selected', $content);
            $content = str_replace("{{ selectedAmministratore }}", '', $content);
            $content = str_replace("{{ selectedDocente }}", '', $content);
            $content = str_replace("{{ error }}", '', $content);
        }
        return $content;
    }
}
