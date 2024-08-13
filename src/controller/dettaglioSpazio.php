<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';

class DettaglioSpazio extends Endpoint
{
    private string $spazio_nome = "";
    public function __construct()
    {
        parent::__construct('spazi/spazio', 'GET');
    }

    public function validate(): bool
    {
        $spazio_nome = $this->get('spazio_nome');
        $this->spazio_nome = $spazio_nome;
        return true;
    }


    public function handle(): void
    {
        $this->validate();
        $page = new DettaglioSpazioPage($this->spazio_nome);
        $page->setPath("spazi/spazio?spazio_nome=" . $this->spazio_nome);
        echo $page->render();
    }
}
