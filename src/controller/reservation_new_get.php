<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/page/prenotazioneFormPage.php';
require_once 'message.php';

class ReservationNewGet extends Endpoint
{
    private $giorno;
    private $dalle_ore;
    private $alle_ore;
    private $posizione;
    private $descrizione;

    public function __construct()
    {
        parent::__construct('prenotazioni/nuovo', 'GET');
    }

    public function validate(): bool
    {
        try {
            $this->giorno = $this->get('giorno');
        } catch (Exception $e) {
            $this->giorno = '';
        }
        try {
            $this->dalle_ore = $this->get('dalle-ore');
        } catch (Exception $e) {
            $this->dalle_ore = '';
        }
        try {
            $this->alle_ore = $this->get('alle-ore');
        } catch (Exception $e) {
            $this->alle_ore = '';
        }
        try {
            $this->posizione = intval($this->get('posizione'));
        } catch (Exception $e) {
            $this->posizione = -1;
        }
        try {
            $this->descrizione = $this->get('descrizione');
        } catch (Exception $e) {
            $this->descrizione = '';
        }

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

        $this->validate();

        $page = new PrenotazioneFormPage(
            $this->giorno,
            $this->dalle_ore,
            $this->alle_ore,
            $this->posizione,
            $this->descrizione,
            ''
        );

        $page->setPath($this->getCurrentPath());
        echo $page->render();
    }
}
