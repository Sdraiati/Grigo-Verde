<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';

// classe item
class SpazioItem {

    public function render($values) {
        $item = '<li id="' . $values["Posizione"] . '">' . $values["Nome"];
        if ($values["Byte"]) {
            $item = $item . '<img src="' . $values["Byte"] .' alt="">';
        } else { 
            $item = $item . '<img src="assets/default_space_light_theme.png" alt="">';
        }
        $item = $item . ' </li>';
        return $item;
    }

}

class VisualizzazioneSpaziPage extends Page
{
    private string $tipo;
    private string $data_inizio;
    private string $data_fine;
    private string $error;
    // public $title = 'VisualizzazioneSpazi';
    // public $nav = [
    //     'About us' => 'about_us',
    // ];
    // public $breadcrumb = [
    //     'Home' => '',
    // ];
    // public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    // public $path = '/visualizzazione_spazi';

    public function __construct(string $tipo = "", string $data_inizio = "", string $data_fine = "", string $error = "") {
        // parent::setTitle('Viualizzazione Spazi');
        // parent::setNav([]);
        // parent::setBreadcrumb([
        //     'Home' => '',
        // ]);

        parent::__construct();
        $this->setTitle('<span lang="en">Visualizzazione Spazi </span>');
        $this->setBreadcrumb([]);
        $this->addKeywords([""]);
        $this->setPath('/spazi');

        $this->tipo = $tipo;
        $this->data_inizio = $data_inizio;
        $this->data_fine = $data_fine;
        $this->error = $error;
    }

    private function filtra_spazi($tipo, $data_inizio, $data_fine)
    {

        try {

            $model_spazio = new Spazio();
            $result = $model_spazio->prendi_tutti_con_immagini();

            if ($tipo == "" && $data_inizio == "" && $data_fine == "") {
                return $result;
            }
            $filtered = [];

            if ($tipo != "" || $data_inizio != "" || $data_fine != "") {
                $filtered = $result; 

                if ($tipo != "") {
                    $filtered = [];
                    for ($i=0; $i < count($result); $i++) { 
                        if ($result[$i]["Tipo"] == $tipo) {
                            array_push($filtered, $result[$i]);
                        }
                    }
                }

                if ($data_inizio != "" && $data_fine != "") {
                    $diq = explode(" ", $data_inizio)[0] . " 00:00:00";
                    $dfq = explode(" ", $data_fine)[0] . " 23:59:59";
                  
                    try {

                        $model_prenotazione = new Prenotazione();
                        $prenotazioni = $model_prenotazione->prendi_per_intervallo($diq, $dfq);

                        if (count($prenotazioni) > 0)  {
                            $current_space = $prenotazioni[0]["Spazio"];
                            $overlap = false;

                            for ($i=0; $i < count($prenotazioni); $i++) { 
                                if ($prenotazioni[$i]["Spazio"] != $current_space) { // se la prenotazione corrente ha uno spazio diverso da quello corrente.
                                    $current_space = $prenotazioni[$i]["Spazio"];
                                    $overlap = false; // per il nuovo spazio in esame non vi sono ancora overlap.
                                }
                                if (!$overlap) { // se non vi è ancora un overlap per lo spazio corrente.
                                    $pdi = $prenotazioni[$i]["DataInizio"];
                                    $pdf = $prenotazioni[$i]["DataFine"];

                                    // in questo caso vi è una prenotazione che si sovrappone.
                                    // N.B: funziona ma con il minore stretto.
                                    if (($data_inizio > $pdi && $data_inizio < $pdf) || 
                                        ($data_fine > $pdi && $data_fine < $pdf) || 
                                        ($pdi == $data_inizio && $pdf == $data_fine) || 
                                        ($data_inizio <= $pdi && $data_fine >= $pdf)) { 

                                        $overlap = true; // serve per escludere eventuali prenotazioni aventi lo stesso spazio
                                        for ($j=0; $j < count($filtered); $j++) { 
                                            if ($current_space == $filtered[$j]["Posizione"]) {
                                                array_splice($filtered, $j, 1);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        return false;
                    }
                }
                return $filtered;
            }
            return $result;

        } catch (Exception $e) {
            return false;
        }
    }

    public function render()
    {
        $content = parent::render();
        $intestazione_pagina = $this->getContent('visualizzazione_spazi_page');

        // Renderizzare i filtri attivi se ce ne sono. 
        if ($this->tipo != "") {
            $intestazione_pagina = str_replace('{{ checked-' . $this->tipo . ' }}', 'checked', $intestazione_pagina);
        }
        if ($this->data_inizio != "" && $this->data_fine != "") { 
            $data = explode(" ", $this->data_inizio)[0];
            $start = explode(" ", $this->data_inizio)[1];
            $end = explode(" ", $this->data_fine)[1];

            $intestazione_pagina = str_replace('{{ data }}', 'value="' . $data . '"', $intestazione_pagina);
            $intestazione_pagina = str_replace('{{ start }}', 'value="' . $start . '"', $intestazione_pagina);
            $intestazione_pagina = str_replace('{{ end }}', 'value="' . $end . '"', $intestazione_pagina);
        }

        // lista degli spazi
        $query_result = $this->filtra_spazi($this->tipo, $this->data_inizio . ":00", $this->data_fine . ":00");

        if ($query_result) {
            $lista_spazi = "";
            $spazioItem = new SpazioItem();
            for ($i = 0; $i < count($query_result); $i++) {
                $values = [];
                $values["Posizione"] = ($query_result[$i]["Posizione"]);
                $values["Nome"] = ($query_result[$i]["Nome"]);
                $values["Byte"] = ($query_result[$i]["Byte"]);
                $lista_spazi = $lista_spazi . $spazioItem->render($values);
            }

            $intestazione_pagina = str_replace("{{ lista }}", $lista_spazi, $intestazione_pagina);
            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
        } else {
            $messaggio = " <p> non sono stati trovai degli spazi corrispondenti ai parametri della ricerca <p>";
            $intestazione_pagina = str_replace("{{ lista }}", $messaggio, $intestazione_pagina);
            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
        }

        $content = str_replace("href=\"/\"", "href=\"#\"", $content);   // todo: check if and why this is needed (this should never be needed)
        $content = str_replace('{{ base_path }}', BASE_URL, $content);  // todo: check if and why this is needed
        if ($this->error) {
            $content = str_replace("{{ error }}", $this->error, $content);            // idem as above
        } else {
            $content = str_replace("{{ error }}", '', $content);            // todo: check if and why this is needed
        }
        return $content;
    }
}
