<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';

class ReservationUpdatePost extends Endpoint
{
    private $reservation_id;
    private $giorno;
    private $dalle_ore;
    private $alle_ore;
    private $posizione;
    private $descrizione;

    public function __construct()
    {
        parent::__construct('dashboard/prenotazione/modifica', 'POST');
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }

    public function render_with_error($error)
    {
        $page = new PrenotazioneFormPage(
            $this->giorno,
            $this->dalle_ore,
            $this->alle_ore,
            $this->posizione,
            $this->descrizione,
            $error,
            $this->reservation_id,
        );

        $page->setTitle('Modifica prenotazione');
        $page->setPath('dashboard/prenotazione/modifica');
        $page = $page->render();
        $page = str_replace("Crea Prenotazione", "Modifica Prenotazione", $page);
        $page = str_replace("dashboard/nuova-prenotazione", "dashboard/prenotazione/modifica", $page);

        echo $page;
    }

    public function validate(): bool
    {
        $reservation_id = $this->post('id');
        $giorno = $this->post('giorno');
        $dalle_ore = $this->post('dalle-ore');
        $alle_ore = $this->post('alle-ore');
        $posizione = intval($this->post('spazio'));
        $descrizione = $this->post('descrizione');

        if (empty($reservation_id) || empty($giorno) || empty($dalle_ore) || empty($alle_ore) || empty($posizione)) {
            return false;
        }

        $this->reservation_id = $reservation_id;
        $this->giorno = $giorno;
        $this->dalle_ore = $dalle_ore;
        $this->alle_ore = $alle_ore;
        $this->posizione = $posizione;
        $this->descrizione = $descrizione;

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

        // Check if the space is open to the public
        $disponibilita = new Disponibilita();
        if (!$disponibilita->is_open($this->posizione, $this->giorno, $this->dalle_ore, $this->alle_ore)) {
            $this->render_with_error("Lo spazio non è aperto nell'orario specificato");
            return;
        }

        $data_inizio = $this->giorno . ' ' . $this->dalle_ore;
        $data_fine = $this->giorno . ' ' . $this->alle_ore;

        if ($prenotazione->modifica($this->reservation_id, $data_inizio, $data_fine, $this->posizione, $this->descrizione)) {
            $this->redirect('prenotazioni/?prenotazione=' . $this->reservation_id);
        }

        $this->render_with_error("Errore nell'aggiornamento della prenotazione");
    }
}
