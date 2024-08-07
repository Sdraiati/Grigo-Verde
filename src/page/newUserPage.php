<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'model/utente.php';
class newUserPage extends Page
{
    private string $username = '';
    private string $password = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';
    public function __construct(string $username = '', string $password = '', string $nome = '', string $cognome = '', string $ruolo = '', string $error = '')
    {
        parent::__construct();
        $this->setTitle('Nuovo Utente');
        $this->setBreadcrumb([
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

    public function render() : string
    {
        if(!Autenticazione::isLogged())
        {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            return $page->render();
        }
        if (!Autenticazione::is_amministratore()) {
            return "Non hai i permessi per accedere a questa pagina"; //TODO: 403 forbidden page
        }
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('new_user'), $content);

        $content = str_replace("{{ action }}", $this->path, $content);
        $content = str_replace("{{ operazione }}", "Crea", $content);
        $content = str_replace("{{ disabled }}", 'required', $content);
        $content = str_replace("{{ hidden_input }}", '', $content);

        $password_fields =
            '<label for="password"><span lang="en">Password</span> <span class="required"
                                                                        aria-hidden="true">*</span></label>
            <input type="password" id="password" name="password" placeholder="Inserire una password"
                   value="{{ password }}" required>
            <input id="mostra-password" type="checkbox" class="mostra-password" onclick="toggleView()">
            <label for="mostra-password">Mostra <span lang="en">Password</span></label>
            <label for="conferma_password"><span lang="en">Conferma Password</span> <span class="required"
                                                                          aria-hidden="true">*</span></label>
            <input type="password" id="conferma_password" name="conferma_password"
                   placeholder="Conferma la password" required>
            <input id="mostra-conferma" type="checkbox" class="mostra-password" onclick="toggleView(\'conferma_password\')">
            <label for="mostra-conferma">Mostra <span lang="en">Password</span></label>';
        $content = str_replace("{{ password_fields }}", $password_fields, $content);

        if($this->nome != '') {
            $content = str_replace("{{ nome }}", $this->nome, $content);
            $content = str_replace("{{ cognome }}", $this->cognome, $content);
            $content = str_replace("{{ username }}", $this->username, $content);
            $content = str_replace("{{ password }}", $this->password, $content);

            $content = str_replace("{{ selectedDefault }}", '', $content);
            $content = str_replace("{{ selectedAmministratore }}", $this->ruolo == 'Amministratore' ? 'selected' : '', $content);
            $content = str_replace("{{ selectedDocente }}", $this->ruolo == 'Docente' ? 'selected' : '', $content);

            if($this->error != '') {
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