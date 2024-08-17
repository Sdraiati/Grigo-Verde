<?php
include_once 'page.php';
include_once 'loginPage.php';

class EditAvailabilityPage extends Page
{
    private int $posizione = -1;
    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Modifica Disponibilità');
        $this->setBreadcrumb([
            'Spazi' => 'spazi'
        ]);
        $this->setPath('/spazi/modifica/disponibilita');
    }

    public function fetch(): void
    {
        if (isset($_GET['posizione'])) {
            $this->posizione = intval($_GET['posizione']);
        }
    }

    public function render(): string
    {
        $this->fetch();

    }
}