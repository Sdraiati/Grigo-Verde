<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/edit_space.php';
include_once 'model/utente.php';

class editSpacePage extends Page
{
    protected $title = 'Modifica Spazio';
    protected $keywords = [""];
    protected $path = '/spazi/modifica';
    protected $breadcrumb = [
        'Spazi' => 'spazi'
    ];

    private int $posizione = -1;
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;
    private string $error = '';
    public function __construct(
        int $posizione = -1,
        string $nome = "",
        string $descrizione = "",
        string $tipo = "",
        int $n_tavoli = 0,
        string $error = ''
    ) {
        parent::__construct();
        $this->setTitle($this->title);
        $this->setBreadcrumb($this->breadcrumb);
        $this->setPath($this->path);
        $this->addKeywords($this->keywords);

        $this->posizione = $posizione;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->tipo = $tipo;
        $this->n_tavoli = $n_tavoli;
        $this->error = $error;
    }
    private function fetch(): void
    {
        if (isset($_GET['posizione'])) {
            $this->posizione = intval($_GET['posizione']);
        }

        if (
            $this->posizione !== -1 && $this->nome === "" && $this->descrizione === "" && $this->tipo === ""
            && $this->n_tavoli === 0 && $this->error === ""
        ) {
            $spazio = new Spazio();

            if ($spazio->exists($this->posizione)) {
                $result = $spazio->prendi($this->posizione);
                $this->nome = $result['Nome'];
                $this->descrizione = $result['Descrizione'];
                $this->tipo = $result['Tipo'];
                $this->n_tavoli = $result['N_tavoli'];
            } else {
                $this->error = "Spazio non esistente";
            }
        }
    }
    public function render()
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            return $page->render();
        }
        if (!Autenticazione::is_amministratore()) {
            return "Non hai i permessi per accedere a questa pagina"; //TODO: 403 forbidden page
        }

        $this->fetch();
        $image_result = $this->loadImages();
        $html_img = '';
        if ($image_result) {
            //se è un array di array
            if (is_array(reset($image_result))) {
                foreach ($image_result as $img) {
                    $html_img .= $this->renderImagePreviews($img);
                }
            } else {
                $html_img = $this->renderImagePreviews($image_result);
            }
        }

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('edit_space'), $content);

        if (($html_img !== '')) {
            $content = str_replace("{{ images }}", $html_img, $content);
            $content = str_replace("{{ add_image_button }}", '', $content);
        } else {
            $content = str_replace("{{ images }}", '', $content);
            $content = str_replace("{{ add_image_button }}", '<input type="button" id="add_img_button" value="Aggiungi immagine" onclick="addImage()">', $content);
        }

        if ($this->posizione != -1) {
            $content = str_replace("{{ selectedDefault }}", '', $content);

            $content = str_replace("{{ posizione }}", $this->posizione, $content);
            $content = str_replace("{{ nome }}", $this->nome, $content);
            $content = str_replace("{{ descrizione }}", $this->descrizione, $content);
            if ($this->tipo === 'Aula verde') {
                $content = str_replace("{{ selectedVerde }}", 'selected', $content);
                $content = str_replace("{{ selectedRicreativo }}", '', $content);
            } elseif ($this->tipo === 'Spazio ricreativo') {
                $content = str_replace("{{ selectedRicreativo }}", 'selected', $content);
                $content = str_replace("{{ selectedVerde }}", '', $content);
            }
            $content = str_replace("{{ tipo }}", $this->tipo, $content);
            $content = str_replace("{{ n_tavoli }}", $this->n_tavoli, $content);
            if ($this->error !== '')
                $content = str_replace("{{ error }}", parent::error($this->error), $content);
            else
                $content = str_replace("{{ error }}", '', $content);
        } else {
            $content = str_replace("{{ selectedDefault }}", 'selected', $content);

            $content = str_replace("{{ posizione }}", '', $content);
            $content = str_replace("{{ nome }}", '', $content);
            $content = str_replace("{{ descrizione }}", '', $content);
            $content = str_replace("{{ tipo }}", '', $content);
            $content = str_replace("{{ n_tavoli }}", '', $content);
            $content = str_replace("{{ error }}", '', $content);
            $content = str_replace("{{ selectedVerde }}", '', $content);
            $content = str_replace("{{ selectedRicreativo }}", '', $content);
        }
        return $content;
    }
    public function loadImages(): array | null
    {
        $immagine = new Immagine();
        return $immagine->prendi($this->posizione);
    }
    private function renderImagePreviews(array $immagine, int $num = 0): string
    {
        $imageData = $immagine['Byte'];
        $mimeType = htmlspecialchars($immagine['Mime_type']);
        $altText = htmlspecialchars($immagine['Alt']);

        $html = '<div id="image_div_' . $num . '">';
        $html .= '<label for="image_' . $num . '">Carica un\'immagine: </label>';

        $html .= '<input type="file" class="image" name="image_' . $num . '" id="image_' . $num . '" accept="image/png, image/jpg, image/jpeg" onclick="return refreshPreview()">';
        $html .= '<img id="image_preview_' . $num . '" style="max-width: 10vw; display: block;" src="data:' . $mimeType . ';base64,' . $imageData . '" alt="' . $altText . '">';
        $html .= '<label for="img_description_' . $num . '">Descrizione dell\'immagine</label>';
        $html .= '<input type="text" class="img_description" name="img_description_' . $num . '" id="img_description_' . $num . '" placeholder="Descrizione dell\'immagine" value="' . $altText . '">';
        $html .= '<input type="button" value="Rimuovi" onclick="removeImage(' . $num . ')">';
        $html .= '</div>';

        return $html;
    }
}

