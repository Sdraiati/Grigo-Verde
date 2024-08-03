<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/spazio.php';

class NewSpace extends Endpoint
{
    private int $posizione = -1; // -1 non corrisponde a nessuna posizione
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;

    public function __construct()
    {
        parent::__construct('new_space', 'POST');
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

    public function handle() : void
    {
        $posizione = $this->post('posizione');
        $nome = $this->post('nome');
        $descrizione = $this->post('descrizione');
        $tipo = $this->post('tipo');
        $n_tavoli = $this->post('n_tavoli');

        if (!$this->validate()) {
            $page = new NewSpacePage($this->posizione, $this->nome, $this->descrizione, $this->tipo,
                $this->n_tavoli, "Inserire tutti i campi");
            echo $page->render();
        } else {
            $spazio = new Spazio();
            if($spazio->prendi($posizione) !== null) {
                $page = new NewSpacePage($this->posizione, $this->nome, $this->descrizione, $this->tipo,
                    $this->n_tavoli, "Posizione già esistente, sceglierne un'altra o modificare lo spazio esistente");
                echo $page->render();
                return;
            }
            if($spazio->prendi_per_nome($nome) !== null) {
                $page = new NewSpacePage($this->posizione, $this->nome, $this->descrizione, $this->tipo,
                    $this->n_tavoli, "Nome già esistente, sceglierne un altro");
                echo $page->render();
                return;
            }
            $spazio->nuovo($posizione, $nome, $descrizione, $tipo, $n_tavoli);
            echo "Spazio creato";
        }
    }
}