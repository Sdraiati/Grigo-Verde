<?php
include_once 'page.php';
include_once 'loginPage.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/new_space.php';
include_once 'model/utente.php';

class PrenotazioneFormPage extends Page
{
    private int $reservation_id = -1;
    private string $giorno = '';
    private string $dalle_ore = '';
    private string $alle_ore = '';
    private int $posizione = -1;
    private string $descrizione = '';
    private string $error = '';

    public function __construct(string $giorno = '', string $dalle_ore = '', string $alle_ore = '', int $posizione = -1, string $descrizione = '', string $error = '', int $reservation_id = -1)
    {
        parent::__construct();
        parent::setTitle('Nuova Prenotazione');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Cruscotto' => 'cruscotto',
        ]);
        $this->addKeywords(['']);

        $this->reservation_id = $reservation_id;
        $this->giorno = $giorno;
        $this->dalle_ore = $dalle_ore;
        $this->alle_ore = $alle_ore;
        $this->posizione = $posizione;
        $this->descrizione = $descrizione;
        $this->error = $error;
    }

    public function getSpaziOptions()
    {
        $spazio = new Spazio();
        $result = $spazio->prendi_tutti();
        $options = '';
        if ($this->posizione == -1) {
            $options .= '<option value="-1" selected disabled>Seleziona uno spazio</option>\n';
        }
        foreach ($result as $row) {
            if ($this->posizione == $row['Posizione']) {
                $options .= '<option value="' . $row['Posizione'] . '" selected>' . $row['Nome'] . '</option>\n';
                continue;
            }
            $options .= '<option value="' . $row['Posizione'] . '">' . $row['Nome'] . '</option>\n';
        }
        return $options;
    }

    public function render()
    {
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            return $page->render();
        }

        if ($this->giorno == '') {
            $this->giorno = date('Y-m-d');
        }

        if ($this->dalle_ore == '') {
            $current_time = new DateTime();
            if ($current_time->format('i') != '00') {
                $current_time->modify('+1 hour');
                $current_time->setTime($current_time->format('H'), 0);
            }

            $this->dalle_ore = $current_time->format('H:i');
            $current_time->modify('+1 hour');
            $this->alle_ore = $current_time->format('H:i');
        }


        $content = $this->getContent('prenotazione_form');
        if ($this->reservation_id == -1) {
            $content = str_replace("{{ reservation-id }}", "", $content);
        } else {
            $content = str_replace("{{ reservation-id }}", '<input type="hidden" name="id" value="' . $this->reservation_id . '">', $content);
        }

        $content = str_replace("{{ giorno }}", $this->giorno, $content);
        $content = str_replace("{{ dalle-ore }}", $this->dalle_ore, $content);
        $content = str_replace("{{ alle-ore }}", $this->alle_ore, $content);
        $content = str_replace("{{ spazi-options }}", $this->getSpaziOptions(), $content);
        $content = str_replace("{{ descrizione }}", $this->descrizione, $content);
        $content = str_replace("{{ error }}", parent::error($this->error), $content);
        $content = str_replace("{{ content }}", $content, parent::render());
        $content = str_replace("{{ title }}", $this->title, $content);

        /*
        if (Autenticazione::is_amministratore()) {
            // return "Sei un amministratore"; //TODO: nuova prenotazione di un amministratore
            // here we could render a button to create repetitions
        } else {
            // here we take off the space to create repetitions, so that nothing
            // is showed
        }
        */

        return $content;
    }
}
