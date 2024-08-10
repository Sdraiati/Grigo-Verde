<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';
include_once 'model/utente.php';
include_once 'model/immagine.php';

class DettaglioSpazioPage extends Page
{
    private string $spazio_nome = "";

    public function __construct(string $spazio_nome = '')
    {
        parent::__construct();
        $this->setTitle('Dettaglio Spazio');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Spazi' => 'spazi'
        ]);
        $this->setPath('/spazi/spazio');
        $this->addKeywords([]);

        $this->spazio_nome = $spazio_nome;
    }

    public function render()
    {
        $content = parent::render();
        $image = new Immagine();
        $spazio = new Spazio();
        $spazio_data = $spazio->prendi_per_nome($this->spazio_nome);
        if (empty($spazio_data)) {
            $content = str_replace('{{ content }}', "<h1>Questo spazio non esiste</h1>", $content);
            return $content;
        }

        $anteprima = $image->prendi($spazio_data['Posizione']);

        if (!empty($anteprima)) {
            $mime_type = $anteprima['Mime_type'];
            $src = 'data:' . $mime_type . ';base64,' . $anteprima['Byte'];
            $alt = $anteprima['Alt'];
        }

        $content_2 = $this->getContent('dettaglio_spazio');
        $content_2 = str_replace('{{ nome spazio }}', $spazio_data['Nome'], $content_2);
        $content_2 = str_replace('{{ descrizione spazio }}', $spazio_data['Descrizione'], $content_2);
        $content_2 = str_replace('{{ tipo spazio }}', $spazio_data['Tipo'], $content_2);
        $content_2 = str_replace('{{ numero tavoli spazio }}', $spazio_data['N_tavoli'], $content_2);

        if (!empty($anteprima)) {
            $content_2 = str_replace('{{ immagine }}', "<img src='" . $src . "' alt='" . $alt . "' />", $content_2);
        } else {
            $content_2 = str_replace('{{ immagine }}', "nessun'immagine", $content_2);
        }


        $prenotazione = new Prenotazione();
        date_default_timezone_set('Europe/Rome');
        $dataEOraCorrenti = date('Y-m-d H:i:s');
        $prenotazioni_data = $prenotazione->prendi_per_settimana((int)$spazio_data['Posizione'], $dataEOraCorrenti);

        if (empty($prenotazioni_data)) {
            $content_2 = preg_replace(
                '/<p id="descrizione-tabella">.*?<\/table>/s',
                "<p>Non ci sono prenotazioni per questo spazio.</p>",
                $content_2
            );
        }

        $rows = $this->setRowTable($prenotazioni_data);
        $content_2 = str_replace('{{ righe tabella }}', $rows, $content_2);
        $content = str_replace('{{ content }}', $content_2, $content);
        return $content;
    }

    protected function setRowTable($prenotazioni_data)
    {
        $utente = new Utente();
        $rowTemplate = "<tr>
        <td>{{ giorno }}</td>
        <td>{{ ora inizio }}</td>
        <td>{{ ora fine }}</td>
        <td>{{ nome }}</td>
        <td>{{ cognome }}</td>
        <td>{{ dettaglio }}</td>
        </tr>";

        $rows = "";
        $count = count($prenotazioni_data);

        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $prenotazione = $prenotazioni_data[$i];
            $start_date_time = new DateTime($prenotazione['DataInizio']);
            $end_date_time = new DateTime($prenotazione['DataFine']);
            $giorno = $start_date_time->format('Y-m-d');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $currentUser = $utente->prendi($prenotazione['Username']);
            $row = str_replace('{{ giorno }}', $giorno, $rowTemplate);
            $row = str_replace('{{ ora inizio }}', $ora_inizio, $row);
            $row = str_replace('{{ ora fine }}', $ora_fine, $row);
            $row = str_replace('{{ nome }}', $currentUser['Nome'], $row);
            $row = str_replace('{{ cognome }}', $currentUser['Cognome'], $row);
            $row = str_replace('{{ dettaglio }}', '<a href="prenotazioni/dettaglio?prenotazione=' . $prenotazione['Id'] . '">dettaglio</a>', $row);

            $rows .= $row;
        }

        return $rows;
    }
}
