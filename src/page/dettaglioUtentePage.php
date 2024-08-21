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
    private Utente $utente;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Dettaglio Utente');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Utenti' => 'utenti'
        ]);
        $this->setPath('/utenti/utente');
        $this->addKeywords(["dettaglio utente"]);

        $this->prenotazione = new Prenotazione();
        $this->spazio = new Spazio();
        $this->utente = new Utente();
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

    public function getDateInfo($date): array
    {
        $date_time = new DateTime($date);
        $day = $date_time->format('Y-m-d');
        $time = $date_time->format('H:i');
        return ['day' => $day, 'time' => $time];
    }

    public function setRowTable($prenotazioni): string
    {
        $nome_spazio = '';
        $row = '';
        foreach ($prenotazioni as $prenotazione) {
            $row .= '<tr>';

            $nome_spazio = $this->spazio->prendi($prenotazione['Spazio'])['Nome'];
            $row .= '<td>' . $nome_spazio . '</td>';

            $startDateInfo = $this->getDateInfo($prenotazione['DataInizio']);
            $endDateInfo = $this->getDateInfo($prenotazione['DataFine']);

            $row .= '<td><time datetime="' . $startDateInfo['day'] . '">' . $startDateInfo['day'] . '</td>';
            $row .= '<td><time>' . $startDateInfo['time'] . '</time></td>';
            $row .= '<td ><time>' . $endDateInfo['time'] . '</time></td>';
            $row .= '<td><a href="prenotazioni/dettaglio?prenotazione=' .
                $prenotazione['Spazio'] . '" aria-label="dettaglio prenotazione dello spazio ' . $nome_spazio .
                '">dettaglio</a></td>';
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
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            return $page->render();
        }

        $this->fetch();
        $content = parent::render();

        if(empty($this->utente->prendi($this->username))) {
            $content = str_replace("{{ content }}", $this->error("Utente non esistente"), $content);
        }

        $content = str_replace("{{ content }}", $this->getContent('dettaglio_utente'), $content);
        $content = str_replace("{{ username }}", $this->username, $content);
        $content = str_replace("{{ nome }}", $this->nome, $content);
        $content = str_replace("{{ cognome }}", $this->cognome, $content);
        $content = str_replace("{{ ruolo }}", $this->ruolo, $content);

        $prenotazioni = $this->prenotazione->prendi_per_utente($this->username);

        if (empty($prenotazioni)) {
            // remove p with id "descrizione tabella"
            $content = preg_replace('/<p\s+id="descrizione tabella"[^>]*>.*?<\/p>/is', '', $content);
            // remove the table
            $content = preg_replace('/<table.*?>(.*?)<\/table>/s', '<p>Nessuna prenotazione trovata.</p>', $content);
        } else {
            $content = str_replace("{{ table-rows }}", $this->setRowTable($prenotazioni), $content);
        }

        return $content;
    }
}
