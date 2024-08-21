<?php

include_once 'page.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/controller/autenticazione.php';

class PrenotazioneDetailPage extends Page
{
    private $reservation_id;

    public function __construct($reservation_id)
    {
        parent::__construct();
        parent::setTitle('Dettaglio Prenotazione');
        parent::setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Prenotazioni' => 'prenotazioni',
        ]);
        $this->addKeywords(["Prenotazione", "Dettaglio"]);

        $this->reservation_id = $reservation_id;
    }

    public function render()
    {
        $prenotazioni = new Prenotazione();

        $reservation = $prenotazioni->prendi_by_id($this->reservation_id);
        $this->addKeywords([$reservation['NomeSpazio']]);

        $this->setDescription("Visualizza i dettagli di una prenotazione dello spazio " . $reservation['NomeSpazio'] . " del Liceo Grigoletti di Pordenone.");

        $start_date_time = new DateTime($reservation['DataInizio']);
        $end_date_time = new DateTime($reservation['DataFine']);
        $giorno = $start_date_time->format('d/m/Y');
        $ora_inizio = $start_date_time->format('H:i');
        $ora_fine = $end_date_time->format('H:i');

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('prenotazione_dettaglio'), $content);

        $content = str_replace("{{ giorno }}", $giorno, $content);
        $content = str_replace("{{ ora_inizio }}", $ora_inizio, $content);
        $content = str_replace("{{ ora_fine }}", $ora_fine, $content);
        $content = str_replace("{{ nome }}", $reservation['Nome'], $content);
        $content = str_replace("{{ cognome }}", $reservation['Cognome'], $content);
        $content = str_replace("{{ nome_aula }}", $reservation['NomeSpazio'], $content);
        $content = str_replace("{{ descrizione }}", $reservation['Descrizione'], $content);
        $content = str_replace("{{ id_prenotazione }}", $this->reservation_id, $content);

        if (!Autenticazione::isLogged() || (Autenticazione::getLoggedUser() !== $reservation['Username'] && !Autenticazione::is_amministratore())) {
            $content = preg_replace('/<aside.*?<\/aside>/is', '', $content);
            $content = str_replace('class="actions-content"', '', $content);
        }

        return $content;
    }
}
