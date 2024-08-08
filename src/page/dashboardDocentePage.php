<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';
include_once 'model/utente.php';
include_once 'model/immagine.php';

class DashboardDocentePage extends Page
{
    private string $docente_nome = "";

    public function __construct(string $docente_nome = '')
    {
        parent::__construct();
        $this->setTitle('Dashboard Docente');
        $this->setBreadcrumb(['dashboard' => 'docente']);
        $this->setPath('dashboard/docente');//dashboard/docente
        $this->addKeywords([]);

        $this->docente_nome = $docente_nome;
    }

    public function render()
    {
        $content = parent::render();
        $prenotazione = new Prenotazione();
        $user = new Utente();
        $prenotazioni = $prenotazione->prendi_by_user($this->docente_nome);
        $utente = $user->prendi($this->docente_nome);
        if(empty($prenotazioni))
        {
            $content = str_replace('{{ content }}', "<h1>Non hai ancora fatto prenotazioni.</h1>", $content);
            return $content;
        }

        $content_2 = $this->getContent('dashboard_docente');
        $content_2 = str_replace('{{ username }}', $this->docente_nome, $content_2);
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
        <td>{{ data inizio }}</td>
        <td>{{ data fine }}</td>
        <td>{{ descrizione }}</td>
        </tr>";

        $rows = "";
        $count = count($prenotazioni_data);
        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $row = str_replace('{{ data inizio }}', $prenotazione['DataInizio'], $rowTemplate);
            $row = str_replace('{{ data fine }}', $prenotazione['DataFine'], $row);
            $row = str_replace('{{ descrizione }}', $prenotazione['Descrizione'], $row);

            $rows .= $row;
        }

        return $rows;
    }
}
