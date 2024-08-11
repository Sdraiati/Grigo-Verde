<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';
require_once 'message.php';

class ReservationNew extends Endpoint
{
    private $giorno;
    private $dalle_ore;
    private $alle_ore;
    private $posizione;
    private $descrizione;

    public function __construct()
    {
        parent::__construct('prenotazioni/nuovo', 'POST');
    }

    public function render_with_error($error)
    {
        $page = new PrenotazioneFormPage(
            $this->giorno,
            $this->dalle_ore,
            $this->alle_ore,
            $this->posizione,
            $this->descrizione,
            $error
        );
        $page->setPath('prenotazioni/nuovo');

        echo $page->render();
    }

    public function validate(): bool
    {
        $giorno = $this->post('giorno');
        $dalle_ore = $this->post('dalle-ore');
        $alle_ore = $this->post('alle-ore');
        $posizione = intval($this->post('spazio'));
        $descrizione = $this->post('descrizione');

        if (empty($giorno) || empty($dalle_ore) || empty($alle_ore) || empty($posizione)) {
            return false;
        }

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

        // Check if the space is open to the public
        $disponibilita = new Disponibilita();
        if (!$disponibilita->is_open($this->posizione, $this->giorno, $this->dalle_ore, $this->alle_ore)) {
            $this->render_with_error("Lo spazio non è aperto nell'orario specificato");
            return;
        }

        $data_inizio = $this->giorno . ' ' . $this->dalle_ore;
        $data_fine = $this->giorno . ' ' . $this->alle_ore;

        // Check if the space is available
        $prenotazione = new Prenotazione();
        if (!$prenotazione->is_available($this->post('spazio'), $data_inizio, $data_fine)) {
            $this->render_with_error("Lo spazio non è disponibile nell'orario specificato");
            return;
        }

        $username = Autenticazione::getLoggedUser();

        // Check if the user has already booked something in the same time slot
        if (!$prenotazione->user_already_booked($username, $data_inizio, $data_fine)) {
            $this->render_with_error("Hai già prenotato un altro spazio nello stesso orario");
            return;
        }

        if (!$prenotazione->nuovo($data_inizio, $data_fine, $username, $this->posizione, $this->descrizione)) {
            $this->render_with_error("Errore nella creazione della prenotazione");
        }

        Message::set("Prenotazione creata con successo");
        $this->redirect('cruscotto');
    }
}
