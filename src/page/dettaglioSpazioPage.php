<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';

class DettaglioSpazioPage extends Page
{
    private string $spazio_nome = "";
    public $title = 'Dettaglio Spazio';
    public $nav = [
        'About us' => 'about_us',
        'Login' => 'login'
    ];
    public $breadcrumb = [];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/';
    public function __construct(string $spazio_nome = '')
    {
        parent::setTitle('Dettagli Spazio');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);

        $this->spazio_nome = $spazio_nome;
    }

    public function render()
    {
        $content = parent::render();
        $content = $this->getContent('layout');

        $spazio = new Spazio();
        $spazio_data = $spazio->prendi_per_nome($this->spazio_nome);

        $content = str_replace('{{ title }}', $this->title . ' - Grigo Verde', $content);
        $content = str_replace('{{ description }}', 'This is a description', $content);
        $content = str_replace('{{ keywords }}', implode(', ', $this->keywords), $content);
        $content = str_replace('{{ page_path }}', $this->path, $content);
        $nav = new ReferenceList($this->nav);
        $content = str_replace('{{ menu }}', $nav->render(), $content);
        $breadcrumb = new Breadcrumb($this->breadcrumb, $this->title);
        $content = str_replace('{{ breadcrumbs }}', $breadcrumb->render(), $content);

        if(empty($spazio_data))
        {
            $content = str_replace('{{ content }}', "<h1>questo spazio non esiste</h1>", $content);
            return $content;
        }
        else{
            $content_2 = $this->getContent('dettaglio_spazio');
            $content_2 = str_replace('{{ nome spazio }}', $spazio_data['Nome'], $content_2);
            $content_2 = str_replace('{{ descrizione spazio }}', $spazio_data['Descrizione'], $content_2);
            $content_2 = str_replace('{{ tipo spazio }}', $spazio_data['Tipo'], $content_2);
            $content_2 = str_replace('{{ numero tavoli spazio }}', $spazio_data['N_tavoli'], $content_2);

            $prenotazione = new Prenotazione();
            date_default_timezone_set('Europe/Rome');
            $dataEOraCorrenti = date('Y-m-d H:i:s');
            $prenotazioni_data = $prenotazione->prendi_per_settimana((int)$spazio_data['Posizione'], $dataEOraCorrenti);

            if(empty($prenotazioni_data))
            {
                $content_2 = str_replace('{{ prenotazioni }}', "<p>Non ci sono prenotazioni per questo spazio.</p>", $content_2);
            }
            else{
                $table = "<table>
                            <caption>Informazioni sulle prenotazioni</caption>
                            <thead>
                                <tr>
                                    <th scope='col'>ID</th>
                                    <th scope='col'>Data Inizio</th>
                                    <th scope='col'>Data Fine</th>
                                    <th scope='col'>Utente</th>
                                    <th scope='col'>Descrizione</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{ righe tabella }}
                            </tbody>
                        </table>";
                
                $rowTemplate = "<tr>
                                    <td>{{ prenotazione id }}</td>
                                    <td>{{ data inizio }}</td>
                                    <td>{{ data fine }}</td>
                                    <td>{{ username }}</td>
                                    <td>{{ descrizione }}</td>
                                </tr>";

                $rows = "";

                foreach ($prenotazioni_data as $prenotazione) {

                    $row = str_replace('{{ prenotazione id }}', $prenotazione['Id'], $rowTemplate);
                    $row = str_replace('{{ data inizio }}', $prenotazione['DataInizio'],$row);
                    $row = str_replace('{{ data fine }}', $prenotazione['DataFine'], $row);
                    $row = str_replace('{{ username }}', $prenotazione['Username'], $row);
                    $row = str_replace('{{ descrizione }}', $prenotazione['Descrizione'], $row);
                    
                    $rows .= $row;
                }
                
                $table = str_replace('{{ righe tabella }}', $rows, $table);
                $content_2 = str_replace('{{ prenotazioni }}', $table, $content_2);
            }

            $content = str_replace('{{ content }}', $content_2, $content);
            return $content;
        } 
    }
}
