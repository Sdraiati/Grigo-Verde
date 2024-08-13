<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'model/utente.php';
class editPasswordPage extends Page
{
    private $error;

    public function __construct(string $error = '')
    {
        parent::__construct();
        $this->setTitle('Modifica Password');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Cruscotto' => 'cruscotto',
        ]);
        $this->setPath('/cruscotto/modifica-password');
        $this->addKeywords([""]);

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

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('edit_password'), $content);
        $content = str_replace("{{ action }}", $this->path, $content);

        $content = $this->error ? str_replace("{{ error }}", $this->error($this->error), $content) :
            str_replace("{{ error }}", '', $content);

        return $content;
    }
}
