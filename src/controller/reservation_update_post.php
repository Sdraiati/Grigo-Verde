<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';
require_once $project_root . '/page/unauthorized.php';
require_once 'message.php';


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
        parent::__construct('prenotazioni/modifica', 'POST');
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
        $page->setPath('prenotazione/modifica');
        $page->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Cruscotto' => 'cruscotto',
            'Dettaglio Prenotazione' => 'prenotazioni/dettaglio?prenotazione=' . $this->reservation_id,
        ]);
        $page = $page->render();

        $page = str_replace("Nuova Prenotazione", "Modifica Prenotazione", $page);
        $page = str_replace("prenotazioni/nuovo", "prenotazioni/modifica", $page);

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
            $page->setPath('login');

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
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            echo $page->render();
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

        $reservation = $prenotazione->prendi_by_id($this->reservation_id);

        if (!$prenotazione->elimina($this->reservation_id)) {
            $this->render_with_error("Errore nell'aggiornamento della prenotazione");
            return;
        }

        // Check if the space is available
        $prenotazione = new Prenotazione();
        if (!$prenotazione->is_available($this->post('spazio'), $data_inizio, $data_fine)) {
            $prenotazione->nuovo($reservation['DataInizio'], $reservation['DataFine'], $reservation['Username'], $reservation['Spazio'], $reservation['Descrizione']);
            $reservation_id = $prenotazione->prendi_by($reservation['DataInizio'], $reservation['DataFine'], $reservation['Spazio'])['Id'];
            $this->reservation_id = $reservation_id;
            $this->render_with_error("Lo spazio non è disponibile nell'orario specificato");
            return;
        }

        $username = Autenticazione::getLoggedUser();

        // Check if the user has already booked something in the same time slot
        if (!$prenotazione->user_already_booked($username, $data_inizio, $data_fine)) {
            $prenotazione->nuovo($reservation['DataInizio'], $reservation['DataFine'], $reservation['Username'], $reservation['Spazio'], $reservation['Descrizione']);
            $reservation_id = $prenotazione->prendi_by($reservation['DataInizio'], $reservation['DataFine'], $reservation['Spazio']);
            $this->reservation_id = $reservation_id;
            $this->render_with_error("Hai già prenotato un altro spazio nello stesso orario");
            return;
        }

        if (!$prenotazione->nuovo($data_inizio, $data_fine, $username, $this->posizione, $this->descrizione)) {
            $this->render_with_error("Errore nell'aggiornamento della prenotazione");
        }

        Message::set("Prenotazione aggiornata con successo");
        $this->redirect('cruscotto');
    }
}
