<?php

include_once 'page.php';
include_once 'model/spazio.php';

// classe item
class SpazioItem {

    // renderizza uno spazio
    // $params: array contenente i valori dei campi spazio
    public function render($values) {
        $item "<li>"

        // costruzione dell'item.
        
        return $item
    }
}

class VisualizzazioneSpaziPage extends Page
{
    private string $tipo = '';
    private $data = '';
    public $title = 'VisualizzazioneSpazi';
    public $nav = [
        'About us' => 'about_us',
    ];
    public $breadcrumb = [
        'Home' => '',
    ];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/visualizzazione_spazi';

    private function filtra_spazi($tipo, $data) {

        // query da modificare in quanto gestisce solamente il tipo.
        $query = "SELECT * FROM SPAZIO 
        JOIN PRENOTAZIONE ON SPAZIO.Posizione = PRENOTAZIONE.Spazio
        JOIN DISPONIBILITA ON SPAZIO.Posizione = DISPONIBILITA.Spazio
        WHERE SPAZIO.Tipo = ? AND NOT PRENOTAZIONE.data = ?";
        
        if ($tipo == "") {
            // regex
            $tipo = ".*"
        }
        if ($data == "") {
            $data = ".*"
        }
        $params = [
            ['type' => 's', 'value' => $tipo],
            ['type' => 's', 'value' => $data]
        ];
    }

    public function __construct(string $tipo = "", string $data = "") {
        parent::setTitle('Viualizzazione Spazi');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);

        $this->$tipo = $tipo;
        $this->$data = $data;
    }

    public function replace_content($content) {
        // $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        // $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }

    public function render() 
    {
        $content = parent::render();
        $items =  $this->filtra_spazi($tipo, $data); // questo è un array di SpazioItems che deve essere rimpiazzato a {{ content }}
        
        // in base ad items costruire {{ content }}
        $lista_spazi = '' 

        // contenuto che varia in base agli spazi.
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
