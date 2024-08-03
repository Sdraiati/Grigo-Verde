<?php

include_once 'page.php';
include_once 'model/spazio.php';

// classe item
class SpazioItem {

    // renderizza uno spazio
    // $params: array contenente i valori dei campi spazio
    public function render($values) {
        
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

        $query = "SELECT * FROM SPAZIO WHERE Tipo = ?";
        
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
        return $this->get_all($query, []);
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
        
        $model_spazio = new Spazio();
        $items =  $model_spazio->prendi_tutti(); // questo è un array di SpazioItems che deve essere rimpiazzato a {{ content }}
        
        // contenuto che varia in base agli spazi.
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
