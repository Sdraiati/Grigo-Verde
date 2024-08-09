<?php
include_once 'page.php';
include_once 'loginPage.php';
include_once 'model/utente.php';
include_once 'model/prenotazione.php';
include_once 'model/spazio.php';

class DettaglioUtentePage extends Page
{
    private string $username = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';
    private Prenotazione $prenotazione;
    private Spazio $spazio;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Dettaglio Utente');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Utenti' => 'utenti'
        ]);
        $this->setPath('/utenti/utente');
        $this->addKeywords([""]);

        $this->prenotazione = new Prenotazione();
        $this->spazio = new Spazio();
    }

    public function fetch(): void
    {
        if (isset($_GET['username'])) {
            $this->username = $_GET['username'];
        } else {
            //echo "Utente non specificato";
            return;
        }

        if ($this->username !== '' && $this->nome === '' && $this->cognome === '' && $this->ruolo === '') {
            $utente = new Utente();

            if ($utente->prendi($this->username) !== null) {
                $result = $utente->prendi($this->username);
                $this->nome = $result['Nome'];
                $this->cognome = $result['Cognome'];
                $this->ruolo = $result['Ruolo'];
            } else {
                //echo "Utente non esistente";
            }
        }
    }

    public function setRowTable($prenotazioni): string
    {
        $nome_spazio = '';
        $row = '';
        foreach ($prenotazioni as $prenotazione) {
            $row .= '<tr>';

            $nome_spazio = $this->spazio->prendi($prenotazione['Spazio'])['Nome'];
            $row .= '<td data-title="nome spazio">' . $nome_spazio . '</td>';

            $row .= '<td data-title="data inizio"> <time datetime="' . $prenotazione['DataInizio'] . '">' . $prenotazione['DataInizio'] . '</time></td>';
            $row .= '<td data-title="data fine"> <time datetime="' . $prenotazione['DataFine'] . '">' . $prenotazione['DataFine'] . '</time></td>';
            $row .= '<td data-title="descrizione">' . $prenotazione['Descrizione'] . '</td>';
            $row .= '</tr>';
        }
        return $row;
    }

    public function render(): string
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            return $page->render();
        }
        if (!Autenticazione::is_amministratore()) {
            return "Non hai i permessi per accedere a questa pagina"; //TODO: 403 forbidden page
        }

        $this->fetch();

        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('dettaglio_utente'), $content);
        $content = str_replace("{{ username }}", $this->username, $content);
        $content = str_replace("{{ nome }}", $this->nome, $content);
        $content = str_replace("{{ cognome }}", $this->cognome, $content);
        $content = str_replace("{{ ruolo }}", $this->ruolo, $content);

        $prenotazioni = $this->prenotazione->prendi_per_utente($this->username);

        if (empty($prenotazioni)) {
            return "Nessuna prenotazione trovata";
        } else {
            $content = str_replace("{{ table-rows }}", $this->setRowTable($prenotazioni), $content);
        }

        return $content;
    }
}

