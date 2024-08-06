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

    public function validate($posizione, $nome, $descrizione, $tipo, $n_tavoli): bool
    {
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

    public function handle(): void
    {
        $this->posizione = $this->post('posizione');
        $this->nome = $this->post('nome');
        $this->descrizione = $this->post('descrizione');
        $this->tipo = $this->post('tipo');
        $this->n_tavoli = intval($this->post('n_tavoli'));

        if (!$this->validate($this->posizione, $this->nome, $this->descrizione, $this->tipo, $this->n_tavoli)) {
            $page = new NewSpacePage(
                $this->posizione,
                $this->nome,
                $this->descrizione,
                $this->tipo,
                $this->n_tavoli,
                "Inserire tutti i campi"
            );
            echo $page->render();
        } else {
            $spazio = new Spazio();
            if ($spazio->prendi($this->posizione) !== null) {
                $page = new NewSpacePage(
                    $this->posizione,
                    $this->nome,
                    $this->descrizione,
                    $this->tipo,
                    $this->n_tavoli,
                    "Posizione già esistente, sceglierne un'altra o modificare lo spazio esistente"
                );
                echo $page->render();
                return;
            }
            if ($spazio->prendi_per_nome($this->nome) !== null) {
                $page = new NewSpacePage(
                    $this->posizione,
                    $this->nome,
                    $this->descrizione,
                    $this->tipo,
                    $this->n_tavoli,
                    "Nome già esistente, sceglierne un altro"
                );
                echo $page->render();
                return;
            }
            $spazio->nuovo($this->posizione, $this->nome, $this->descrizione, $this->tipo, $this->n_tavoli);
            echo "Spazio creato";

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
                if($file['size'] === 0) {
                    return;
                }
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $page = new NewSpacePage(
                        $this->posizione,
                        $this->nome,
                        $this->descrizione,
                        $this->tipo,
                        $this->n_tavoli,
                        "Errore nel caricamento dell'immagine"
                    );
                    echo $page->render();
                    return;
                }
                if ($file['size'] > 1048576) {
                    $page = new NewSpacePage(
                        $this->posizione,
                        $this->nome,
                        $this->descrizione,
                        $this->tipo,
                        $this->n_tavoli,
                        "Errore: l'immagine è troppo grande, dimensione massima 1MB"
                    );
                    echo $page->render();
                    return;
                }
                $allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array(mime_content_type($file['tmp_name']), $allowed_mime_types)) {
                    $page = new NewSpacePage(
                        $this->posizione,
                        $this->nome,
                        $this->descrizione,
                        $this->tipo,
                        $this->n_tavoli,
                        "Errore: il tipo di file non è supportato"
                    );
                    echo $page->render();
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
            echo "Immagini salvate";
        }
    }
}
