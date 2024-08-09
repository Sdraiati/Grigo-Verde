<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/spazio.php';

// classe item
class SpazioItem {

    // renderizza uno spazio
    // $params: array contenente i valori dei campi spazio
    public function render($values) {
        $item = '<li id="' . $values["Posizione"] . '">' . $values["Nome"];
        if ($values["Byte"]) {
            $item = $item . '<img src="' . $values["Byte"] .' alt="">';
        } else { // immagine di default
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
    // public $title = 'VisualizzazioneSpazi';
    // public $nav = [
    //     'About us' => 'about_us',
    // ];
    // public $breadcrumb = [
    //     'Home' => '',
    // ];
    // public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    // public $path = '/visualizzazione_spazi';

    public function __construct(string $tipo = "", string $data_inizio = "", string $data_fine = "") {
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
    }

    private function filtra_spazi($tipo, $data_inizio, $data_fine)
    {

        $debug_query = "SELECT * FROM SPAZIO LEFT JOIN IMMAGINE ON SPAZIO.Posizione = IMMAGINE.Spazio;";
        $params = [];

        // prendere un'istanza del db.
        $db = Database::getInstance();
        $stmt = $db->bindParams($debug_query, $params);

        if ($stmt === false) {
            return false;
        }
        try {

            $filtered = []; 
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // DEBUG
            // var_dump($tipo);
            // var_dump($data_inizio);
            // var_dump($data_fine);

            if ($tipo == "" && $data_inizio == "" && $data_fine == "") {
                return $result;
            }
            $filtered = [];

            //var_dump($result);

            if ($tipo != "" || $data_inizio != "" || $data_fine != "") {
                $filtered = $result; // questo è da cambiare.

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
                    $query = 'SELECT * FROM PRENOTAZIONE WHERE DataInizio >= ? AND DataFine <= ? ORDER BY PRENOTAZIONE.Spazio';
                    $params = [
                        ['type' => 's', 'value' => $diq],
                        ['type' => 's', 'value' => $dfq],
                    ];
                    $stmt = $db->bindParams($query, $params);
                    if ($stmt == false) {
                        return false;
                    }
                    try {
                        $stmt->execute();
                        $prenotazioni = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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

        // Renderizzare i filtri se ce ne sono. 
        if ($this->tipo != "") {
            $checked = '<input type="radio" id="tipo_' . $this->tipo .  '" name="tipo" value="' . $this->tipo . '" checked>';
            $unchecked = '<input type="radio" id="tipo_' . $this->tipo . '" name="tipo" value="' . $this->tipo . '">';
            $intestazione_pagina = str_replace($unchecked, $checked, $intestazione_pagina);
        }

        if ($this->data_inizio != "" && $this->data_fine != "") { 
            $data = explode(" ", $this->data_inizio)[0];
            $start = explode(" ", $this->data_inizio)[1];
            $end = explode(" ", $this->data_fine)[1];

            $date_picker = '<input type="date" id="data" name="data"/>';
            $date_picker_r = '<input type="date" id="data" name="data" value="' . $data .  '"/>';
            $start_picker = '<input type="time" id="orario_inizio" name="orario_inizio">';
            $start_picker_r = '<input type="time" id="orario_inizio" name="orario_inizio" value="' . $start .'">';
            $end_picker = '<input type="time" id="orario_fine" name="orario_fine">';
            $end_picker_r = '<input type="time" id="orario_fine" name="orario_fine" value="' . $end .'">';

            $intestazione_pagina = str_replace($date_picker, $date_picker_r, $intestazione_pagina);
            $intestazione_pagina = str_replace($start_picker, $start_picker_r, $intestazione_pagina);
            $intestazione_pagina = str_replace($end_picker, $end_picker_r, $intestazione_pagina);
        }

        // lista degli spazi
        $lista_debug = $this->filtra_spazi($this->tipo, $this->data_inizio . ":00", $this->data_fine . ":00");

        if ($lista_debug) {
            $lista_spazi = "";
            $spazioItem = new SpazioItem();
            for ($i = 0; $i < count($lista_debug); $i++) {
                $values = [];
                $values["Posizione"] = ($lista_debug[$i]["Posizione"]);
                $values["Nome"] = ($lista_debug[$i]["Nome"]);
                $values["Byte"] = ($lista_debug[$i]["Byte"]);
                $lista_spazi = $lista_spazi . $spazioItem->render($values);
            }

            if ($lista_spazi == "") {
                $lista_spazi = "non sono stati trovai degli spazi corrispondenti ai parametri della ricerca";
            }

            $intestazione_pagina = str_replace("{{ lista }}", $lista_spazi, $intestazione_pagina);

            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
            $content = str_replace("href=\"/\"", "href=\"#\"", $content);   // todo: check if and why this is needed (this should never be needed)
            $content = str_replace('{{ base_path }}', BASE_URL, $content);  // todo: check if and why this is needed
            $content = str_replace("{{ error }}", '', $content);            // todo: check if and why this is needed
        } else {
            $lista_spazi = " <p> non sono stati trovai degli spazi corrispondenti ai parametri della ricerca <p>";

            $intestazione_pagina = str_replace("{{ lista }}", $lista_spazi, $intestazione_pagina);

            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
            $content = str_replace("href=\"/\"", "href=\"#\"", $content);   // idem as above
            $content = str_replace('{{ base_path }}', BASE_URL, $content);  // idem as above
            $content = str_replace("{{ error }}", '', $content);            // idem as above
        }
        return $content;
    }
}
