<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';
include_once 'model/utente.php';
include_once 'model/immagine.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';

class DashboardAmministratorePage extends Page
{
    private string $amministratore_nome = "";

    public function __construct(string $amministratore_nome = '')
    {
        parent::__construct();
        $this->setTitle('Dashboard Amministratore');
        $this->setBreadcrumb(['dashboard' => 'amministratore']);
        $this->setPath('dashboard/amministratore');
        $this->addKeywords([]);

        $this->amministratore_nome = $amministratore_nome;
    }

    public function render()
    {
        $content = parent::render();
        $prenotazione = new Prenotazione();
        $user = new Utente();
        $prenotazioni = $prenotazione->prendi_all();
        $utente = $user->prendi($this->amministratore_nome);
        if(empty($prenotazioni))
        {
            $content = str_replace('{{ content }}', "<h1>".$this->amministratore_nome.", non hai ancora fatto prenotazioni.</h1>", $content);
            return $content;
        }
        $content_2 = $this->getContent('dashboard_amministratore');
        $content_2 = str_replace('{{ username }}', $this->amministratore_nome, $content_2);
        $content_2 = str_replace('{{ nome }}', $utente['Nome'], $content_2);
        $content_2 = str_replace('{{ cognome }}', $utente['Cognome'], $content_2);
        $rows = $this->setRowTable($prenotazioni);
        $content_2 = str_replace('{{ righe tabella }}', $rows, $content_2);
        $content = str_replace('{{ content }}', $content_2, $content);
        return $content;
    }

    protected function setRowTable($prenotazioni_data)
    {
        $rowTemplate = "<tr>
        <td>{{ giorno }}</td>
        <td>{{ ora inizio }}</td>
        <td>{{ ora fine }}</td>
        <td>{{ dettaglio }}</td>
        </tr>";

        $rows = "";
        $count = count($prenotazioni_data);
        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $start_date_time = new DateTime($prenotazione['DataInizio']);
            $end_date_time = new DateTime($prenotazione['DataFine']);
            $giorno = $start_date_time->format('Y-m-d');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $row = str_replace('{{ giorno }}', $giorno, $rowTemplate);
            $row = str_replace('{{ ora inizio }}', $ora_inizio, $row);
            $row = str_replace('{{ ora fine }}', $ora_fine, $row);
            $row = str_replace('{{ dettaglio }}', '<a href="prenotazioni/dettaglio?prenotazione='.$prenotazione['Id'].'">dettaglio</a>', $row);

            $rows .= $row;
        }

        return $rows;
    }
}
