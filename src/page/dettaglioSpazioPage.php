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
        $this->setBreadcrumb(['Spazi' => 'spazi']);
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

        $anteprima = $image->prendi($spazio_data['Posizione']);
        if(!empty($anteprima))
        {
            $mime_type = $anteprima['Mime_type'];
            $src = 'data:' . $mime_type . ';base64,' . $anteprima['Byte'];
            $alt = $anteprima['Alt'];
        }

        if (empty($spazio_data)) {
            $content = str_replace('{{ content }}', "<h1>questo spazio non esiste</h1>", $content);
            return $content;
        } else {
            $content_2 = $this->getContent('dettaglio_spazio');
            $content_2 = str_replace('{{ nome spazio }}', $spazio_data['Nome'], $content_2);
            $content_2 = str_replace('{{ descrizione spazio }}', $spazio_data['Descrizione'], $content_2);
            $content_2 = str_replace('{{ tipo spazio }}', $spazio_data['Tipo'], $content_2);
            $content_2 = str_replace('{{ numero tavoli spazio }}', $spazio_data['N_tavoli'], $content_2);
            if(!empty($anteprima)){$content_2 = str_replace('{{ immagine }}', "<img src='".$src."' alt='".$alt."' />", $content_2);}
            else{$content_2 = str_replace('{{ immagine }}', "nessun'immagine", $content_2);}
                

            $prenotazione = new Prenotazione();
            date_default_timezone_set('Europe/Rome');
            $dataEOraCorrenti = date('Y-m-d H:i:s');
            $prenotazioni_data = $prenotazione->prendi_per_settimana((int)$spazio_data['Posizione'], $dataEOraCorrenti);
            $table = "<table aria-describedby='descrizione-tabella'>
                                <caption>Informazioni sulle prenotazioni</caption>
                                <thead>
                                    <tr>
                                        <th scope='col'>Data Inizio</th>
                                        <th scope='col'>Data Fine</th>
                                        <th scope='col'>Nome</th>
                                        <th scope='col'>Cognome</th>
                                        <th scope='col'>Descrizione</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{ righe tabella }}
                                </tbody>
                            </table>
                            <p id='descrizione-tabella'>questa tabella mostra le prenotazioni per lo spazio</p>";
            
            if (empty($prenotazioni_data)) {
                $content_2 = str_replace($table, "<p>Non ci sono prenotazioni per questo spazio.</p>", $content_2);
            } else {
                
                $rows = $this->setRowTable($prenotazioni_data);
            
                $content_2 = str_replace('{{ righe tabella }}', $rows, $content_2);
            }

            $content = str_replace('{{ content }}', $content_2, $content);
            return $content;
        }
    }

    protected function setRowTable($prenotazioni_data)
    {
        $utente = new Utente();
        $rowTemplate = "<tr>
        <td>{{ data inizio }}</td>
        <td>{{ data fine }}</td>
        <td>{{ nome }}</td>
        <td>{{ cognome }}</td>
        <td>{{ descrizione }}</td>
        </tr>";

        $rows = "";
        $count = count($prenotazioni_data);
        for ($i = 0; $i < $count; $i++) {
            $prenotazione = $prenotazioni_data[$i];
            $currentUser = $utente->prendi($prenotazione['Username']); 
            $row = str_replace('{{ data inizio }}', $prenotazione['DataInizio'], $rowTemplate);
            $row = str_replace('{{ data fine }}', $prenotazione['DataFine'], $row);
            $row = str_replace('{{ nome }}', $currentUser['Nome'], $row);
            $row = str_replace('{{ cognome }}', $currentUser['Cognome'], $row);
            $row = str_replace('{{ descrizione }}', $prenotazione['Descrizione'], $row);

            $rows .= $row;
        }

        return $rows;
    }
}
