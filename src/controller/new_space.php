<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/spazio.php';
include_once $project_root . '/model/immagine.php';

class NewSpace extends Endpoint
{
    private int $posizione = -1; // -1 non corrisponde a nessuna posizione
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;

    public function __construct()
    {
        parent::__construct('spazi/nuovo', 'POST');
    }

    public function validate(): bool
    {
        $posizione = intval($this->post('posizione'));
        $nome = $this->post('nome');
        $descrizione = $this->post('descrizione');
        $tipo = $this->post('tipo');
        $n_tavoli = intval($this->post('n_tavoli'));

        if (empty($posizione) || empty($nome) || empty($tipo)) {
            return false;
        }
        $this->posizione = $posizione;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->tipo = $tipo;
        $this->n_tavoli = $n_tavoli;
        return true;
    }

    protected function render_new_space_with_error($error)
    {
        $page = new NewSpacePage(
            $this->posizione,
            $this->nome,
            $this->descrizione,
            $this->tipo,
            $this->n_tavoli,
            $error
        );
        $page->setPath('spazi/nuovo');
        echo $page->render();
    }

    public function handle(): void
    {
        $posizione = $this->post('posizione');
        $nome = $this->post('nome');
        $descrizione = $this->post('descrizione');
        $tipo = $this->post('tipo');
        $n_tavoli = $this->post('n_tavoli');

        if (!$this->validate()) {
            $this->render_new_space_with_error('Inserire tutti i campi');
            return;
        } else {
            $spazio = new Spazio();
            if ($spazio->prendi($posizione) !== null) {
                $this->render_new_space_with_error("Posizione già esistente, sceglierne un'altra o modificare lo spazio esistente");
                return;
            }
            if ($spazio->prendi_per_nome($nome) !== null) {
                $this->render_new_space_with_error("Nome già esistente, sceglierne un altro");
                return;
            }
            $spazio->nuovo($posizione, $nome, $descrizione, $tipo, $n_tavoli);
            // echo "Spazio creato";

            $uploadedFiles = $_FILES;
            $descriptions = [];
            foreach ($_POST as $key => $value) {
                if (str_starts_with($key, 'img_description_')) {
                    //take just the number from the key (after the last '_')
                    $num = substr($key, strrpos($key, '_') + 1);
                    $descriptions[intval($num)] = $value;
                }
            }

            $images = [];
            foreach ($uploadedFiles as $key => $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $this->render_new_space_with_error("Errore nel caricamento dell'immagine");
                    return;
                }
                if ($file['size'] > 1048576) {
                    $this->render_new_space_with_error("Errore: l'immagine è troppo grande, dimensione massima 1MB");
                    return;
                }
                $allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array(mime_content_type($file['tmp_name']), $allowed_mime_types)) {
                    $this->render_new_space_with_error("Errore: il tipo di file non è supportato");
                    return;
                }
                $tmpName = $file['tmp_name'];
                $fileName = basename($file['name']);

                $num = substr($key, strrpos($key, '_') + 1);
                $num = intval($num);

                // Creare un array associativo per le immagini
                $images[] = [
                    'tmp_name' => $tmpName,
                    'file_name' => $fileName,
                    'description' => $descriptions[$num]
                ];
            }

            // Salvataggio delle immagini
            foreach ($images as $image) {
                //$img = file_get_contents($image['tmp_name']);
                $mime_type = mime_content_type($image['tmp_name']);

                // read the file as base64
                $fp = fopen($image['tmp_name'], 'rb');
                $base64 = base64_encode(fread($fp, filesize($image['tmp_name'])));

                $imgDB = new Immagine();
                $imgDB->nuovo($base64, $image['description'], $mime_type, $this->posizione);

                fclose($fp);
            }
            // echo "Immagini salvate";
        }
        // $this->redirect("dettaglio-spazio?spazio_nome=...); TODO
    }
}
