<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';

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
            $this->render_with_error("Errore nella validazione dei dati");
            return;
        }

        $username = Autenticazione::getLoggedUser();
        $prenotazione = new Prenotazione();

        if (!Autenticazione::is_amministratore() && $prenotazione->prendi_by_id($this->reservation_id)['Username'] !== $username) {
            echo 403; // Todo: unauthorized
            return;
        }

        $prenotazione->elimina($this->reservation_id);
        $this->redirect('dashboard');
    }
}
