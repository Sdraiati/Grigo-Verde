<?php

include_once 'page.php';
include_once 'model/spazio.php';
include_once 'model/prenotazione.php';
include_once 'model/utente.php';
include_once 'model/immagine.php';

class DashboardDocentePage extends Page
{
    private string $docente_nome = "";

    public function __construct(string $docente_nome = '')
    {
        parent::__construct();
        $this->setTitle('Dashboard Docente');
        $this->setBreadcrumb(['dashboard' => 'docente']);
        $this->setPath('dashboard/docente');//dashboard/docente
        $this->addKeywords([]);

        $this->docente_nome = $docente_nome;
    }

    public function render()
    {
        $content = parent::render();
        return $content;
    }
}
