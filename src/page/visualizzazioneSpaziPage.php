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
    private string $tipo;
    private $data;
    public $title = 'VisualizzazioneSpazi';
    public $nav = [
        'About us' => 'about_us',
    ];
    public $breadcrumb = [
        'Home' => '',
    ];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/visualizzazione_spazi';

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
        // params è un array che contiene i filtri della ricerca
        // nel caso in cui questi fossero nulli il page_path rimane invariato.
        // in base al contenuto di questo array il page_path viene modificato in questo modo:
        // /visualizzazione_spazi?param1=$params[0]&param2=$params[1] ...

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
