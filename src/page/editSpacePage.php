<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/edit_space.php';
include_once 'model/utente.php';

class editSpacePage extends Page
{
    private int $posizione = -1;
    private string $nome = '';
    private string $descrizione = '';
    private string $tipo = '';
    private int $n_tavoli = 0;
    private string $error = '';
    public function __construct(int $posizione=-1, string $nome="", string $descrizione="", string $tipo="", int $n_tavoli=0,
                                string $error="")
    {
        parent::setTitle('Modifica Spazio');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => '',
            'Spazi' => 'spazi',
        ]);

        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->tipo = $tipo;
        $this->n_tavoli = $n_tavoli;
        $this->error = $error;
    }
    private function fetch() : void
    {
        if(isset($_GET['posizione']))
            $this->posizione = intval($_GET['posizione']);

        if($this->posizione!==-1 && $this->nome==="" && $this->descrizione==="" && $this->tipo===""
            && $this->n_tavoli===0 && $this->error==="")
        {
            $spazio = new Spazio();
            
            if ($spazio->exists($this->posizione))
            {
                $result = $spazio->prendi($this->posizione);
                $this->nome = $result['Nome'];
                $this->descrizione = $result['Descrizione'];
                $this->tipo = $result['Tipo'];
                $this->n_tavoli = $result['N_tavoli'];
            }
            else {
                $this->error = "Spazio non esistente";
            }
        }
    }
    public function render()
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage("", "",
                'Devi effettuare il login per accedere a questa pagina');
            return $page->render();
        }
        if (!Autenticazione::is_amministratore()) {
            return "Non hai i permessi per accedere a questa pagina"; //TODO: 403 forbidden page
        }

        $this->fetch();

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('edit_space'), $content);
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