<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/page/prenotazioneDetailPage.php';
require_once $project_root . '/page/resource_not_found.php';
require_once $project_root . '/page/unauthorized.php';

class ReservationDetail extends Endpoint
{
    private $reservation_id;

    public function __construct()
    {
        parent::__construct('prenotazioni/dettaglio', 'GET');
    }

    public function validate(): bool
    {
        $reservation_id = $this->get('prenotazione');
        if (!is_numeric($reservation_id) || $reservation_id <= 0) {
            return false;
        }

        $this->reservation_id = $reservation_id;
        return true;
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }

    public function handle(): void
    {
        if (!$this->validate()) {
            $page = new ResourceNotFoundPage();
            $page->setPath($this->path);
            echo $page->render();
            return;
        }

        $prenotazioni = new Prenotazione();

        $reservation = $prenotazioni->prendi_by_id($this->reservation_id);
        if ($reservation === null) {
            $page = new ResourceNotFoundPage();
            $page->setPath($this->path);
            echo $page->render();
            return;
        }

        $page = new PrenotazioneDetailPage($this->reservation_id);
        $page->setPath('prenotazioni/dettaglio?prenotazione=' . $this->reservation_id);
        echo $page->render();
    }
}
