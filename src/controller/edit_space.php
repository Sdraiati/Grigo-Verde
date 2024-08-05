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
        $posizione = intval($this->sanitizeInput($this->post('posizione')));
        $nome = $this->sanitizeInput($this->post('nome'));
        $descrizione = $this->sanitizeInput($this->post('descrizione'));
        $tipo = $this->sanitizeInput($this->post('tipo'));
        $n_tavoli = intval($this->sanitizeInput($this->post('n_tavoli')));

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
        }
    }
}