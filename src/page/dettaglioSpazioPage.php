<?php

include_once 'page.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/spazio.php';
include_once $project_root . '/model/prenotazione.php';
include_once $project_root . '/model/utente.php';
include_once $project_root . '/model/immagine.php';
include_once $project_root . '/controller/autenticazione.php';
include_once 'model/disponibilità.php';

class DettaglioSpazioPage extends Page
{
    private string $spazio_nome = "";
    private string $tab_disp = "";

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
        $content_2 = str_replace('{{ posizione }}', $spazio_data['Posizione'], $content_2);

        if (!empty($anteprima)) {
            $content_2 = str_replace('{{ immagine }}', "<img src='" . $src . "' alt='" . $alt . "'>", $content_2);
        } else {
            $content_2 = str_replace('{{ immagine }}', "nessun'immagine", $content_2);
        }


        $prenotazione = new Prenotazione();
        date_default_timezone_set('Europe/Rome');
        $dataEOraCorrenti = date('Y-m-d H:i:s');
        $prenotazioni_data = $prenotazione->prendi_per_settimana((int)$spazio_data['Posizione'], $dataEOraCorrenti);

        if (empty($prenotazioni_data)) {
            $content_2 = preg_replace(
                '/<p id="descrizione-tabella-prenotazioni".*?<\/table>/s',
                "<p>Non ci sono prenotazioni per questo spazio.</p>",
                $content_2
            );
        }

        $disponibilità = new Disponibilita();
        $lista_disponibilità = $disponibilità->prendi($spazio_data['Posizione']);
        if (empty($lista_disponibilità)) {
            $content_2 = preg_replace(
                '/<p id="descrizione-tabella-disponibilità".*?<\/table>/s',
                "<p>Non ci sono disponibilità per questo spazio.</p>",
                $content_2
            );
        }

        $rows = $this->setRowTablePrenotazioni($prenotazioni_data);
        $content_2 = str_replace('{{ righe tabella prenotazioni }}', $rows, $content_2);

        $rows = $this->setRowTableDisponibilita($lista_disponibilità);
        $content_2 = str_replace('{{ righe tabella disponibilità }}', $rows, $content_2);


        $content = str_replace('{{ content }}', $content_2, $content);


        if (!Autenticazione::isLogged()) {
            $content = str_replace('class="actions-content"', '', $content);
            $content = preg_replace('/<aside>.*<\/aside>/s', '', $content);
        } else if (!Autenticazione::is_amministratore()) {
            $content = preg_replace(
                '/<form action="spazi\/modifica".*?<\/form>\s*<form action="spazi\/elimina".*?<\/form>/s',
                '',
                $content
            );
        }

        return $content;
    }

    protected function setRowTablePrenotazioni($prenotazioni_data)
    {
        $utente = new Utente();
        $rowTemplate = '<tr>
        <td>{{ nome }} {{ cognome }}</td>
            <td scope="col"><time datetime="{{ data }}">{{ data }}</time></td>
            <td scope="col"><time>{{ inizio }}</time></td>
            <td scope="col"><time>{{ fine }}</time></td>
            <td scope="col"><a href="prenotazioni/dettaglio?prenotazione={{ id }}">dettaglio</a></td>
        </tr>';

        $rows = "";
        $count = count($prenotazioni_data);

        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $prenotazione = $prenotazioni_data[$i];
            $start_date_time = new DateTime($prenotazione['DataInizio']);
            $end_date_time = new DateTime($prenotazione['DataFine']);
            $giorno = $start_date_time->format('d/m/Y');
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $currentUser = $utente->prendi($prenotazione['Username']);
            $row = str_replace('{{ nome }}', $currentUser['Nome'], $rowTemplate);
            $row = str_replace('{{ cognome }}', $currentUser['Cognome'], $row);
            $row = str_replace('{{ data }}', $giorno, $row);
            $row = str_replace('{{ inizio }}', $ora_inizio, $row);
            $row = str_replace('{{ fine }}', $ora_fine, $row);
            $row = str_replace('{{ id }}', $prenotazione['Id'], $row);

            $rows .= $row;
        }

        return $rows;
    }

    protected function setRowTableDisponibilita($lista_disponibilità)
    {
        $rowTemplate = '<tr>
                <td>{{ mese }}</td>
                <td scope="col">{{ giorno }}</td>
                <td scope="col"><time>{{ inizio }}</time></td>
                <td scope="col"><time>{{ fine }}</time></td>
            </tr>';

        $rows = "";
        $count = count($lista_disponibilità);

        for ($i = 0; $i < $count; $i++) {
            $disponibilità = $lista_disponibilità[$i];
            $start_date_time = new DateTime($disponibilità['Orario_apertura']);
            $end_date_time = new DateTime($disponibilità['Orario_chiusura']);
            $ora_inizio = $start_date_time->format('H:i');
            $ora_fine = $end_date_time->format('H:i');
            $row = str_replace('{{ mese }}', $disponibilità['Mese'], $rowTemplate);
            $row = str_replace('{{ giorno }}', $disponibilità['Giorno_settimana'], $row);
            $row = str_replace('{{ inizio }}', $ora_inizio, $row);
            $row = str_replace('{{ fine }}', $ora_fine, $row);

            $rows .= $row;
        }

        return $rows;
    }
}
