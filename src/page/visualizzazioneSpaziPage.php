<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';

// classe item
class SpazioItem
{
    static private $template = '<li> 
            <p class="title">{{ Nome }}</p> 
            <img src="{{ Byte }}" alt=""> 
            <a href="spazi/spazio?spazio_nome={{ Nome }}"> visualizza dettaglio </a> 
        </li>';

    public function render($values)
    {
        $item = str_replace('{{ Nome }}', $values["Nome"], self::$template);
        if ($values["Byte"]) {
            $item = str_replace('{{ Byte }}', $values["Byte"], $item);
        } else {
            $item = str_replace('{{ Byte }}', 'assets/default_spazio_image.png', $item);
        }
        return $item;
    }
}

class VisualizzazioneSpaziPage extends Page
{
    private string $tipo;
    private string $data_inizio;
    private string $data_fine;
    private string $error;
    // public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];

    public function __construct(string $tipo = "", string $data_inizio = "", string $data_fine = "", string $error = "")
    {
        parent::__construct();
        parent::setTitle('Viualizzazione Spazi');
        parent::setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords([""]);
        $this->setPath('/spazi');

        $this->tipo = $tipo;
        $this->data_inizio = $data_inizio;
        $this->data_fine = $data_fine;
        $this->error = $error;
    }

    private function filtra_per_ora($prenotazioni, $filtered, $data_inizio, $data_fine)
    {
        $overlap = false;
        $current_space = $prenotazioni[0]["Spazio"];

        for ($i = 0; $i < count($prenotazioni); $i++) {
            if ($prenotazioni[$i]["Spazio"] != $current_space) { // se la prenotazione corrente ha uno spazio diverso da quello corrente.
                $current_space = $prenotazioni[$i]["Spazio"];
                $overlap = false; // per il nuovo spazio in esame non vi sono ancora overlap.
            }
            if (!$overlap) { // se non vi è ancora un overlap per lo spazio corrente.
                $pdi = $prenotazioni[$i]["DataInizio"];
                $pdf = $prenotazioni[$i]["DataFine"];

                if (($data_inizio > $pdi && $data_inizio < $pdf) ||
                    ($data_fine > $pdi && $data_fine < $pdf) ||
                    ($pdi == $data_inizio && $pdf == $data_fine) ||
                    ($data_inizio <= $pdi && $data_fine >= $pdf)
                ) {

                    $overlap = true; // serve per escludere eventuali prenotazioni aventi lo stesso spazio
                    for ($j = 0; $j < count($filtered); $j++) {
                        if ($current_space == $filtered[$j]["Posizione"]) {
                            array_splice($filtered, $j, 1);
                        }
                    }
                }
            }
        }
        return $filtered;
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
                    for ($i = 0; $i < count($result); $i++) {
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

                        if (count($prenotazioni) > 0) {
                            $filtered = $this->filtra_per_ora($prenotazioni, $filtered, $data_inizio, $data_fine);
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

        // Renderizzare i filtri attivi se ce ne sono. 
        if ($this->tipo != "") {
            $repl = "{{ checked-" . $this->tipo . " }}";
            $intestazione_pagina = str_replace($repl, "checked", $intestazione_pagina);
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

        if ($this->error) {
            $content = str_replace("{{ error }}", $this->error($this->error), $content);            // idem as above
        } else {
            $content = str_replace("{{ error }}", '', $content);            // todo: check if and why this is needed
        }

        $content = preg_replace('/{{.*?}}/', '', $content); // remove all other placeholders
        return $content;
    }
}
