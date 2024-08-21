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
        $this->setTitle('Cruscotto');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->setPath('cruscotto');
        $this->addKeywords([]);

        $this->docente_nome = $docente_nome;
    }

    public function render()
    {
        $content = parent::render();
        $prenotazione = new Prenotazione();
        $user = new Utente();
        $prenotazioni = $prenotazione->prendi_per_utente_time($this->docente_nome);
        $utente = $user->prendi($this->docente_nome);

        $content_2 = $this->getContent('dashboard_docente');
        $content_2 = str_replace('{{ username }}', $this->docente_nome, $content_2);
        $content_2 = str_replace('{{ nome }}', $utente['Nome'], $content_2);
        $content_2 = str_replace('{{ cognome }}', $utente['Cognome'], $content_2);

        if (empty($prenotazioni)) {
            $content_2 = preg_replace('/<p id="descrizione-tabella".*<\/table>/s', "<p>Non hai ancora fatto prenotazioni.</p>", $content_2);
            $content = str_replace('{{ content }}', $content_2, $content);
            return $content;
        }

        $rows = $this->setRowTable($prenotazioni);
        $content_2 = str_replace('{{ righe tabella }}', $rows, $content_2);
        $content = str_replace('{{ content }}', $content_2, $content);
        return $content;
    }

    protected function setRowTable($prenotazioni_data)
    {
        $rowTemplate = '<tr>
            <td><time datetime="{{ data }}">{{ data }}</time></td>
            <td><time>{{ inizio }}</time></td>
            <td><time>{{ fine }}</time></td>
            <td><a href="prenotazioni/dettaglio?prenotazione={{ id }}">dettaglio</a></td>
        </tr>';

        $rows = "";
        $count = count($prenotazioni_data);
        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $start_date_time = new DateTime($prenotazione['DataInizio']);
            $end_date_time = new DateTime($prenotazione['DataFine']);
            $giorno = $start_date_time->format('d/m/Y');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $row = str_replace('{{ data }}', $giorno, $rowTemplate);
            $row = str_replace('{{ inizio }}', $ora_inizio, $row);
            $row = str_replace('{{ fine }}', $ora_fine, $row);
            $row = str_replace('{{ id }}', $prenotazione['Id'], $row);

            $rows .= $row;
        }

        return $rows;
    }
}
