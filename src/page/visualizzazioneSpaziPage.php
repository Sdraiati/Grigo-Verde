<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/spazio.php';

// classe item
class SpazioItem
{

    // renderizza uno spazio
    // $params: array contenente i valori dei campi spazio
    public function render($values)
    {
        $item = '<li id="' . $values["Posizione"] . '">' . $values["Nome"] . " </li>";
        return $item;
    }
}

class VisualizzazioneSpaziPage extends Page
{
    private string $tipo;
    private string $data_inizio;
    private string $data_fine;
    //    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];

    public function __construct(string $tipo = "", string $data_inizio = "", string $data_fine = "")
    {
        parent::__construct();
        parent::setTitle('Viualizzazione Spazi');
        parent::setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);

        $this->tipo = $tipo;
        $this->data_inizio = $data_inizio;
        $this->data_fine = $data_fine;
    }

    private function filtra_spazi($tipo, $data_inizio, $data_fine)
    {

        $debug_query = "SELECT * FROM SPAZIO;";
        // binding dei parametri
        $params = [];

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

            //var_dump($result);

            if ($tipo != "" || $data_inizio != "" || $data_fine != "") {
                if ($tipo != "") {
                    for ($i = 0; $i < count($result); $i++) {
                        if ($result[$i]["Tipo"] == $tipo) {
                            array_push($filtered, $result[$i]);
                        }
                    }
                }
                if ($data_inizio != "" && $data_fine != "") {
                    $diq = explode(" ", $data_inizio)[0] . " 00:00:00";
                    $dfq = explode(" ", $data_fine)[0] . " 23:59:59";
                    $query = 'SELECT * FROM PRENOTAZIONE WHERE DataInizio >= ? AND DataFine <= ?';
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

                        if (count($prenotazioni) != 0) {
                            // filtro per data
                            $current_space = $prenotazioni[0]["Spazio"];
                            $overlap = false;
                            for ($i = 0; $i < count($prenotazioni); $i++) {
                                if ($prenotazioni[$i]["Spazio"] != $current_space) {
                                    if ($overlap) { // l'intervallo selezionato va in conflitto con le altre prenotazioni.
                                        for ($j = 0; $j < count($filtered); $j++) {
                                            if ($filtered[$j]["Spazio"] == $current_space) {
                                                array_splice($filtered, $j, $j);
                                                $j = count($filtered); // uscire dal ciclo non appena si trova l'elemento da scartare.
                                            }
                                        }
                                    }
                                    $current_space = $prenotazioni[$i]["Spazio"];
                                }
                                $pdi = $prenotazioni[$i]["DataInizio"];
                                $pdf = $prenotazioni[$i]["DataFine"];
                                if (($pdi > $data_inizio && $pdi < $data_fine) || ($pdf > $data_inizio && $pdf < $data_fine)) {
                                    // in questo caso vi è una prenotazione che si sovrappone.
                                    $overlap = true;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
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

        if ($this->data_inizio != "") {
            $unchecked = '<input type="datetime-local" name="Data_inizio" id="Data_inizio" />';
            $checked = '<input type="datetime-local" name="Data_inizio" id="Data_inizio" value="' . $this->data_inizio . '"/>';
            $intestazione_pagina = str_replace($unchecked, $checked, $intestazione_pagina);
        }

        if ($this->data_fine != "") {
            $unchecked = '<input type="datetime-local" name="Data_fine" id="Data_fine" />';
            $checked = '<input type="datetime-local" name="Data_fine" id="Data_fine" value="' . $this->data_fine . '"/>';
            $intestazione_pagina = str_replace($unchecked, $checked, $intestazione_pagina);
        }

        // lista degli spazi
        $lista_debug = $this->filtra_spazi($this->tipo, $this->data_inizio, $this->data_fine);

        var_dump($lista_debug);
        if ($lista_debug) {
            $lista_spazi = "";
            $spazioItem = new SpazioItem();
            for ($i = 0; $i < count($lista_debug); $i++) {
                $values = [];
                $values["Posizione"] = ($lista_debug[$i]["Posizione"]);
                $values["Nome"] = ($lista_debug[$i]["Nome"]);
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
