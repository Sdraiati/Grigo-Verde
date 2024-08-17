<?php
include_once 'page.php';
include_once 'loginPage.php';
include_once 'model/utente.php';
require_once 'page/unauthorized.php';
require_once 'model/disponibilità.php';
require_once 'model/spazio.php';

class AvailabilityPage extends Page
{
    private string $nome_spazio = "";
    private string $error = '';

    public function __construct(string $nome_spazio = "", string $error="")
    {
        parent::__construct();
        $this->setTitle('Modifica disponibilità');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
            'Spazi' => 'spazi',
            'Disponibilità' => 'spazi/disponibilita'
        ]);
        $this->setPath('/spazi/disponibilita');
        $this->addKeywords([""]);
        $this->nome_spazio = $nome_spazio;
        $this->error = $error;
    }
    private function fetch(): void
    {
        if (isset($_GET['nome_spazio'])) {
            $this->nome_spazio = ($_GET['nome_spazio']);
        }
    }

    public function getAvailability(): array
    {
        $disponibilita = new Disponibilita();
        $spazio = new Spazio();
        $posizione = $spazio->prendi_per_nome($this->nome_spazio)['Posizione'];
        return $disponibilita->prendi($posizione);
    }

    function renderAvailability($availabilityResults): string
    {
        $output = '';
        $availabilityCount = 0;

        foreach ($availabilityResults as $entry) {
            // Rimuove i secondi da 'Orario_apertura' e 'Orario_chiusura'
            $orario_apertura = substr($entry['Orario_apertura'], 0, 5);
            $orario_chiusura = substr($entry['Orario_chiusura'], 0, 5);

            $output .= '<div class="availability" id="availability_div_' . $availabilityCount . '">';
            $output .= '<h3>Disponibilità ' . ($availabilityCount + 1) . '</h3>';

            // Selezione dei mesi
            $output .= '<label for="availability_month_' . $availabilityCount . '">Seleziona i mesi</label>';
            $output .= '<div id="availability_month_' . $availabilityCount . '">';
            $months = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
            foreach ($months as $month) {
                $checked = ($entry['Mese'] === $month) ? 'checked' : '';
                $output .= '<input type="checkbox" name="availability_month_' . $availabilityCount . '[]" value="' . $month . '" ' . $checked . '>';
                $output .= '<label for="' . $month . '">' . $month . '</label>';
            }
            $output .= '</div>';

            // Selezione dei giorni
            $output .= '<label for="availability_day_' . $availabilityCount . '">Seleziona i giorni</label>';
            $output .= '<div id="availability_day_' . $availabilityCount . '">';
            $week_days = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"];
            foreach ($week_days as $day) {
                $checked = ($entry['Giorno_settimana'] === $day) ? 'checked' : '';
                $output .= '<input type="checkbox" name="availability_day_' . $availabilityCount . '[]" value="' . $day . '" ' . $checked . '>';
                $output .= '<label for="' . $day . '">' . $day . '</label>';
            }
            $output .= '</div>';

            // Orario di inizio e fine
            $output .= '<label for="availability_start_hour_' . $availabilityCount . '">Inserisci l\'ora di inizio</label>';
            $output .= '<input type="time" name="availability_start_hour_' . $availabilityCount . '" id="availability_start_hour_' . $availabilityCount . '" value="' . $orario_apertura . '" required>';

            $output .= '<label for="availability_end_hour_' . $availabilityCount . '">Inserisci l\'ora di fine</label>';
            $output .= '<input type="time" name="availability_end_hour_' . $availabilityCount . '" id="availability_end_hour_' . $availabilityCount . '" value="' . $orario_chiusura . '" required>';

            $output .= '<input type="button" value="Rimuovi" onclick="removeAvailability(\'availability_div_' . $availabilityCount . '\')">';

            $output .= '</div>';
            $availabilityCount++;
        }
        $output .= '<input type="hidden" id="availabilityCount" value="' . $availabilityCount . '">';

        return $output;
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
        $availability = [];
        if($this->nome_spazio === ''){
            $this->error = "Spazio non specificato";
        }
        else {
            $availability = $this->getAvailability();
        }

        $content = parent::render();
        if($this->error !== ''){
            return str_replace("{{ content }}", $this->error($this->error), $content);
        }
        $content = str_replace("{{ content }}", $this->getContent('availability'), $content);

        $content = str_replace("{{ availability }}", $this->renderAvailability($availability), $content);

        return str_replace("{{ nome_spazio }}", $this->nome_spazio, $content);
    }
}