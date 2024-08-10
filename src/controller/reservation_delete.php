<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';
require_once $project_root . '/page/unauthorized.php';
require_once $project_root . '/page/resource_not_found.php';
require_once $project_root . '/page/unauthorized.php';
require_once $project_root . '/page/resource_not_found.php';

class ReservationDelete extends Endpoint
{
    private $reservation_id;

    public function __construct()
    {
        parent::__construct('prenotazione/elimina', 'POST');
    }

    public function validate(): bool
    {
        $reservation_id = $this->post('prenotazione');

        if (empty($reservation_id)) {
            return false;
        }

        $this->reservation_id = $reservation_id;
        return true;
    }

    public function handle(): void
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );

            echo $page->render();
            return;
        }


        if (!$this->validate()) {
            $page = new ResourceNotFoundPage();
            $page->setPath($this->path);
            echo $page->render();
            return;
        }

        $username = Autenticazione::getLoggedUser();
        $prenotazione = new Prenotazione();

        if (!Autenticazione::is_amministratore() && $prenotazione->prendi_by_id($this->reservation_id)['Username'] !== $username) {
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            echo $page->render();
            exit();
        }

        $prenotazione->elimina($this->reservation_id);
        $this->redirect('dashboard');
    }
}
