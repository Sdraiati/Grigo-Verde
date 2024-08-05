<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/spazio.php';

// classe item
class SpazioItem {

    // renderizza uno spazio
    // $params: array contenente i valori dei campi spazio
    public function render($values) {
        $item = '<li id="' . $values["Posizione"] . '">' . $values["Nome"] . " </li>";
        return $item;
    }
}

class VisualizzazioneSpaziPage extends Page
{
    private string $tipo;
    private string $data_inizio;
    private string $data_fine;
    public $title = 'VisualizzazioneSpazi';
    public $nav = [
        'About us' => 'about_us',
    ];
    public $breadcrumb = [
        'Home' => '',
    ];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/visualizzazione_spazi';

    public function __construct(string $tipo = "", string $data_inizio = "", string $data_fine = "") {
        parent::setTitle('Viualizzazione Spazi');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);

        $this->tipo = $tipo;
        $this->data_inizio = $data_inizio;
        $this->data_fine = $data_fine;
    }

    private function rimuovi_spazi_duplicati($values) {

    } 

    private function filtra_spazi($tipo, $data_inizio, $data_fine) {

        // query da modificare in quanto gestisce solamente il tipo.
        // $query = "SELECT * FROM SPAZIO 
        // JOIN PRENOTAZIONE ON SPAZIO.Posizione = PRENOTAZIONE.Spazio
        // JOIN DISPONIBILITA ON SPAZIO.Posizione = DISPONIBILITA.Spazio
        // WHERE SPAZIO.Tipo = ? AND 
        // DISPONIBILITA.Mese = ? AND 
        // (PRENOTAZIONE.DataFine <= ? AND PRENOTAZIONE.DataInizio >= ?) AND 
        // (DISPONIBILITA.Orario_apertura >= ? AND DISPONIBILITA.Orario_chiusura <= ?)";

        $debug_query = "SELECT * FROM SPAZIO LEFT JOIN PRENOTAZIONE ON SPAZIO.Posizione = PRENOTAZIONE.Spazio;";
        
        // binding dei parametri
        $params = [
        //     ['type' => 's', 'value' => $tipo],
        ];

        // prendere un'istanza del db.
        $db = Database::getInstance(); 
        $stmt = $db->bindParams($debug_query, $params);
        
        if ($stmt === false) {
            return false;
        }
        try {

            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $filtered = []; 

            if ($tipo != "" || $data_inizio != "" || $data_fine != "") {
                if ($tipo != "") {
                    for ($i=0; $i < count($result); $i++) { 
                        if ($result[$i]["Tipo"] == $tipo) {
                            array_push($filtered, $result[$i]);
                        }
                    }
                }
                return $filtered;
            } 
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    

    public function replace_content($content) {
        // $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        // $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }

    public function getTipo() {
        return $this->tipo;
    }

    private function debug() {
        $debug_msg = "parametri utilizzati: ";  
        if ($this->tipo != "") {
            $debug_msg = $debug_msg . " " . $this->tipo;
        } 
        if ($this->data != "") {
            $debug_msg = $debug_msg . " " . $this->data;
        } 
        return $debug_msg;
    }

    public function render() 
    {
        $content = parent::render();

        $intestazione_pagina = $this->getContent('visualizzazione_spazi_page'); 

        // Fare sì che vi siano ancora i radio button checked nel caso di filtri di tipo
        if ($this->tipo != "") {
            $checked = '<input type="radio" id="tipo_' . $this->tipo .  '" name="tipo" value="' . $this->tipo . '" checked>';
            $unchecked = '<input type="radio" id="tipo_' . $this->tipo . '" name="tipo" value="' . $this->tipo . '">';
            $intestazione_pagina = str_replace($unchecked, $checked, $intestazione_pagina);
        }

        // lista degli spazi
        $lista_debug = $this->filtra_spazi($this->tipo, $this->data_inizio, $this->data_fine);
        $lista_spazi = "";
        $spazioItem = new SpazioItem();
        for ($i=0; $i < count($lista_debug); $i++) { 
            $values = [];
            $values["Posizione"] = ($lista_debug[$i]["Posizione"]);
            $values["Nome"] = ($lista_debug[$i]["Nome"]);
            $lista_spazi = $lista_spazi . $spazioItem->render($values);
        }
        $intestazione_pagina = str_replace("{{ lista }}", $lista_spazi, $intestazione_pagina);

        $content = str_replace("{{ content }}", $intestazione_pagina, $content);
        
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
