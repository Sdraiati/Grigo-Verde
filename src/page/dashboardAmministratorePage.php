<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';
include_once 'model/utente.php';
include_once 'model/immagine.php';

class DashboardAmministratorePage extends Page
{
    private string $amministratore_nome = "";

    public function __construct(string $amministratore_nome = '')
    {
        parent::__construct();
        $this->setTitle('Dashboard Amministratore');
        $this->setBreadcrumb(['dashboard' => 'amministratore']);
        $this->setPath('dashboard/amministratore');
        $this->addKeywords([]);

        $this->amministratore_nome = $amministratore_nome;
    }

    public function render()
    {
        $content = parent::render();
        return $content;
    }
}
