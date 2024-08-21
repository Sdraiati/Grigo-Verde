<?php

include_once 'page.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/controller/autenticazione.php';

class PrenotazionePage extends Page
{
    private $row_template = '
        <tr>
            <td>{{ spazio }}</td>
            <td>{{ nome }} {{ cognome }}</td>
            <td><time datetime="{{ data }}">{{ data }}</time></td>
            <td><time>{{ inizio }}</time></td>
            <td><time>{{ fine }}</time></td>
            <td><a href="prenotazioni/dettaglio?prenotazione={{ id }}">dettaglio</a></td>
        </tr>';

    public function __construct()
    {
        parent::__construct();
        parent::setTitle('Prenotazioni');
        parent::setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords(["Prenotazioni"]);
        $this->setDescription("Visualizza tutte le prenotazioni delle aree all'aperto del Liceo Grigoletti di Pordenone.");
    }

    private function render_row($reservation)
    {
        $content = str_replace('{{ spazio }}', $reservation['NomeSpazio'], $this->row_template);
        $content = str_replace('{{ nome }}', $reservation['Nome'], $content);
        $content = str_replace('{{ cognome }}', $reservation['Cognome'], $content);
        $content = str_replace('{{ data }}', $reservation['Giorno'], $content);
        $content = str_replace('{{ inizio }}', $reservation['Inizio'], $content);
        $content = str_replace('{{ fine }}', $reservation['Fine'], $content);
        return str_replace('{{ id }}', $reservation['Id'], $content);
    }

    public function render()
    {
        $prenotazioni = new Prenotazione();

        $reservations = $prenotazioni->prendi_all();

        $rows = '';

        foreach ($reservations as $res) {
            $start_date_time = new DateTime($res['DataInizio']);
            $end_date_time = new DateTime($res['DataFine']);
            $giorno = $start_date_time->format('d/m/Y');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $res['Giorno'] = $giorno;
            $res['Inizio'] = $ora_inizio;
            $res['Fine'] = $ora_fine;
            $rows .= $this->render_row($res) . PHP_EOL;
        }

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('prenotazioni'), $content);
        if (empty($reservations)) {
            // remove p with id "descrizione tabella"
            $content = preg_replace('/<p\s+id="descrizione tabella"[^>]*>.*?<\/p>/is', '', $content);
            // remove the table
            $content = preg_replace('/<table.*?>(.*?)<\/table>/s', '<p>Nessuna prenotazione trovata.</p>', $content);
        } else {
            $content = str_replace("{{ table-rows }}", $rows, $content);
        }

        return $content;
    }
}
