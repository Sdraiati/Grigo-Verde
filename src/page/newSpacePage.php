<?php
include_once 'page.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/new_space.php';
class newSpacePage extends Page
{
    private int $posizione = -1;
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;
    private string $error = '';
    public function __construct(int $posizione = -1, string $nome = "", string $descrizione = '', string $tipo= "", int $n_tavoli = 0, string $error = '')
    {
        parent::setTitle('Nuovo Spazio');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => 'home',
            'Spazi' => 'spaces',
            'Nuovo Spazio' => 'new_space',
        ]);

        $this->posizione = $posizione;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->tipo = $tipo;
        $this->n_tavoli = $n_tavoli;
        $this->error = $error;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('new_space'), $content);
        if ($this->posizione != -1) {
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
            $content = str_replace("{{ error }}", parent::error($this->error), $content);
        } else {
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
}