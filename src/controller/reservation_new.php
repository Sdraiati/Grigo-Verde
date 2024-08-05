<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';

class ReservationNew extends Endpoint
{
    private $giorno;
    private $dalle_ore;
    private $alle_ore;
    private $posizione;
    private $descrizione;

    public function __construct()
    {
        parent::__construct('dashboard/nuova-prenotazione', 'POST');
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
            $page = new PrenotazioneFormPage(
                $this->giorno,
                $this->dalle_ore,
                $this->alle_ore,
                $this->posizione,
                $this->descrizione,
                "Inserire tutti i campi"
            );

            echo $page->render();
            return;
        }

        $username = Autenticazione::getLoggedUser();
        $data_inizio = $this->giorno . ' ' . $this->dalle_ore;
        $data_fine = $this->giorno . ' ' . $this->alle_ore;

        $prenotazione = new Prenotazione();

        /* TODO: controllare che lo spazio sia prenotabile nell'arco di tempo specificato */

        if ($prenotazione->nuovo($data_inizio, $data_fine, $username, $this->posizione, $this->descrizione)) {
            $this->redirect('dashboard');
        }

        $page = new PrenotazioneFormPage(
            $this->giorno,
            $this->dalle_ore,
            $this->alle_ore,
            $this->posizione,
            $this->descrizione,
            "Errore nella creazione della prenotazione"
        );

        echo $page->render();
    }
}
