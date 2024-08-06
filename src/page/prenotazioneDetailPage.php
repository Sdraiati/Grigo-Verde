<?php

include_once 'page.php';
$project_root = dirname(__FILE__, 2);

class PrenotazioneDetailPage extends Page
{
    private string $giorno = '';
    private string $ora_inizio = '';
    private string $ora_fine = '';
    private string $nome = '';
    private string $cognome = '';
    private string $nome_aula = '';
    private string $descrizione = '';

    public function __construct(string $giorno, string $ora_inizio, string $ora_fine, string $nome, string $cognome, string $nome_aula, string $descrizione)
    {
        parent::setTitle('Dettaglio Prenotazione');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Dashboard' => 'dashboard',
        ]);

        $this->giorno = $giorno;
        $this->ora_inizio = $ora_inizio;
        $this->ora_fine = $ora_fine;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->nome_aula = $nome_aula;
        $this->descrizione = $descrizione;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('prenotazione_dettaglio'), $content);

        $content = str_replace("{{ giorno }}", $this->giorno, $content);
        $content = str_replace("{{ ora_inizio }}", $this->ora_inizio, $content);
        $content = str_replace("{{ ora_fine }}", $this->ora_fine, $content);
        $content = str_replace("{{ nome }}", $this->nome, $content);
        $content = str_replace("{{ cognome }}", $this->cognome, $content);
        $content = str_replace("{{ nome_aula }}", $this->nome_aula, $content);
        $content = str_replace("{{ descrizione }}", $this->descrizione, $content);

        return $content;
    }
}
