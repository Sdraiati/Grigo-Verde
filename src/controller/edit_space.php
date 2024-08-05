<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/spazio.php';
include_once $project_root . '/model/immagine.php';

class EditSpace extends Endpoint
{
    private int $posizione = -1; // -1 non corrisponde a nessuna posizione
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;

    public function __construct()
    {
        parent::__construct('spazi/modifica', 'POST');
    }

    public function validate(): bool
    {
        $posizione = intval($this->$this->post('posizione'));
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

    public function handle()
    {
        $posizione = $this->post('posizione');
        $nome = $this->post('nome');
        $descrizione = $this->post('descrizione');
        $tipo = $this->post('tipo');
        $n_tavoli = $this->post('n_tavoli');

        if (!$this->validate()) {
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

            $spazio->modifica($posizione, $nome, $descrizione, $tipo, $n_tavoli);
            echo "Spazio modificato";

            $uploadedFiles = $_FILES;
            $descriptions = [];
            foreach ($_POST as $key => $value) {
                if (str_starts_with($key, 'img_description_')) {
                    $num = substr($key, strrpos($key, '_') + 1);
                    $descriptions[intval($num)] = $value;
                }
            }

            $immagine = new Immagine();
            //if spazio has images update them
            if ($immagine->prendi($posizione) !== null) {
                if (empty($uploadedFiles)) {
                    //delete all images
                    $immagine->elimina($posizione);
                }
                foreach ($uploadedFiles as $key => $file) {
                    if ($file['size'] === 0) {
                        //update description
                        foreach ($descriptions as $key => $value) {
                            if($value === '') {
                                $page = new EditSpacePage(
                                    $this->posizione,
                                    $this->nome,
                                    $this->descrizione,
                                    $this->tipo,
                                    $this->n_tavoli,
                                    "L'immagine deve contenere una descrizione"
                                );
                                echo $page->render();
                                return;
                            }
                            $immagine->modifica_descrizione($posizione, $value);
                        }
                    } else {
                        //TODO: questa parte va modificata se si vuole permettere più immagini
                        //delete all images
                        $immagine->elimina($posizione);
                    }
                }
            }
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $key => $file) {
                    if($file['size'] === 0) {
                        return;
                    }
                    if ($file['error'] !== UPLOAD_ERR_OK) {
                        $page = new EditSpacePage(
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
                        $page = new EditSpacePage(
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
                        $page = new EditSpacePage(
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

                    $images[] = [
                        'tmp_name' => $tmpName,
                        'file_name' => $fileName,
                        'description' => $descriptions[$num]
                    ];
                }

                if (!isset($images)) {
                    return;
                }
                // Salvataggio delle nuove immagini
                foreach ($images as $image) {
                    $mime_type = mime_content_type($image['tmp_name']);
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
}

