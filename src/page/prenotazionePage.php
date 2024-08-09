<?php

include_once 'page.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/controller/autenticazione.php';

class PrenotazionePage extends Page
{
    private $row_template = '
        <tr>
            <td scope="col">{{ spazio }}</td>
            <td scope="col">{{ nome }} {{ cognome }}</td>
            <td scope="col">{{ data }}</td>
            <td scope="col">{{ inizio }}</td>
            <td scope="col">{{ fine }}</td>
            <td scope="col"><a href="prenotazioni/dettaglio?prenotazione={{ id }}">dettaglio</a></td>
        </tr>';

    public function __construct()
    {
        parent::__construct();
        parent::setTitle('Prenotazioni');
        parent::setBreadcrumb([]);
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
            $giorno = $start_date_time->format('Y-m-d');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $res['Giorno'] = $giorno;
            $res['Inizio'] = $ora_inizio;
            $res['Fine'] = $ora_fine;
            $rows .= $this->render_row($res) . PHP_EOL;
        }

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('prenotazioni'), $content);
        $content = str_replace("{{ table-rows }}", $rows, $content);


        return $content;
    }
}
