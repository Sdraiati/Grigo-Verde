<?php
require_once 'endpoint.php';
include_once 'model/disponibilità.php';
include_once 'model/spazio.php';
require_once 'message.php';
require_once 'page/availabilityPage.php';

class Availability extends Endpoint
{
    private string $nome_spazio = "";
    private int $posizione = -1;
    private Spazio $spazio;
    private Disponibilita $disponibilita;
    public function __construct()
    {
        parent::__construct('spazi/disponibilita', 'POST');
        $this->disponibilita = new Disponibilita();
        $this->spazio = new Spazio();
    }
    private function validateAvailability(array $availabilityData): bool
    {
        foreach ($availabilityData as $index => $data) {
            if (empty($data['months'])) {
                $page = new AvailabilityPage($this->nome_spazio, "Errore: Seleziona almeno un mese per la disponibilità " . (intval($index) + 1));
                echo $page->render();
                return false;
            }
            if (empty($data['days'])) {
                $page = new AvailabilityPage($this->nome_spazio,"Errore: Seleziona almeno un giorno per la disponibilità " . (intval($index) + 1));
                echo $page->render();
                return false;
            }
            if ($data['start_hour'] === "" || $data['end_hour'] === "") {
                $page = new AvailabilityPage($this->nome_spazio,"Errore: Compila l'orario di inizio e di fine per la disponibilità " . (intval($index) + 1));
                echo $page->render();
                return false;
            }
            if ($data['start_hour'] >= $data['end_hour']) {
                $page = new AvailabilityPage($this->nome_spazio,"Errore: L'orario di fine deve essere successivo a quello di inizio per la disponibilità " . (intval($index) + 1));
                echo $page->render();
                return false;
            }
        }
        return true;
    }
    public function availability(): bool
    {
        $this->disponibilita->elimina_tutte($this->posizione);

        $availabilityData = [];
        foreach ($_POST as $key => $value) {
            // Ignora chiavi che non sono parte delle disponibilità
            if (!str_starts_with($key, 'availability_')) {
                continue;
            }

            $index = str_replace(['availability_month_', 'availability_day_', 'availability_start_hour_',
                'availability_end_hour_'], '', $key);

            if (!isset($availabilityData[$index])) {
                $availabilityData[$index] = [
                    'months' => [],
                    'days' => [],
                    'start_hour' => '',
                    'end_hour' => ''
                ];
            }

            if (str_starts_with($key, 'availability_month_')) {
                if (is_array($value)) {
                    $availabilityData[$index]['months'] = array_merge($availabilityData[$index]['months'], $value);
                } else {
                    $availabilityData[$index]['months'][] = $value;
                }
            }
            if (str_starts_with($key, 'availability_day_')) {
                if (is_array($value)) {
                    $availabilityData[$index]['days'] = array_merge($availabilityData[$index]['days'], $value);
                } else {
                    $availabilityData[$index]['days'][] = $value;
                }
            }
            if (str_starts_with($key, 'availability_start_hour_')) {
                $availabilityData[$index]['start_hour'] = $value;
            }
            if (str_starts_with($key, 'availability_end_hour_')) {
                $availabilityData[$index]['end_hour'] = $value;
            }
        }

        if (!$this->validateAvailability($availabilityData)) {
            return false;
        }

        foreach ($availabilityData as $index => $data) {
            if (!empty($data['months']) && !empty($data['days']) && $data['start_hour'] !== "" && $data['end_hour'] !== "") {
                foreach ($data['months'] as $month) {
                    foreach ($data['days'] as $day) {
                        $this->disponibilita->nuovo($this->posizione, $month, $day, $data['start_hour'], $data['end_hour']);
                    }
                }
            }
        }
        return true;
    }
    public function handle() : void
    {
        $this->nome_spazio = $this->post('nome_spazio');
        $this->posizione = $this->spazio->prendi_per_nome($this->nome_spazio)['Posizione'];
        if(!$this->availability()) {
            return;
        }

        Message::set("Disponibilità modificata con successo");
        $this->redirect("spazi/spazio?spazio_nome=" . $this->nome_spazio);
    }
}