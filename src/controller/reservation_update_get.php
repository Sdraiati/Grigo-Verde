<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/page/prenotazioneFormPage.php';

class ReservationUpdateGet extends Endpoint
{
    private $reservation_id;

    public function __construct()
    {
        parent::__construct('dashboard/prenotazione/modifica', 'GET');
    }

    public function validate(): bool
    {
        $reservation_id = $this->get('prenotazione');
        if (empty($reservation_id)) {
            return false;
        }

        $this->reservation_id = $reservation_id;
        return true;
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
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
            echo 404; // todo: not found
            return;
        }

        $username = Autenticazione::getLoggedUser();
        $prenotazione = new Prenotazione();

        $reservation = $prenotazione->prendi_by_id($this->reservation_id);

        if (!Autenticazione::is_amministratore() && $reservation['Username'] !== $username) {
            echo 403; // Todo: unauthorized
            return;
        }

        $start_date_time = new DateTime($reservation['DataInizio']);
        $end_date_time = new DateTime($reservation['DataFine']);
        $giorno = $start_date_time->format('Y-m-d');
        $ora_inizio = $start_date_time->format('H:i');
        $ora_fine = $end_date_time->format('H:i');

        $page = new PrenotazioneFormPage(
            $giorno,
            $ora_inizio,
            $ora_fine,
            $reservation['Spazio'],
            $reservation['Descrizione'],
            '',
            $this->reservation_id
        );

        $page->setTitle('Modifica prenotazione');
        $page->setPath('dashboard/prenotazione/modifica');
        $page = $page->render();
        $page = str_replace("Crea Prenotazione", "Modifica Prenotazione", $page);
        $page = str_replace("dashboard/nuova-prenotazione", "dashboard/prenotazione/modifica", $page);
        echo $page;
    }
}
