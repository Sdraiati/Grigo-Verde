<?php

include_once 'model/database.php';
include_once 'page.php';
include_once 'model/utente.php';

// classe item
class UtenteItem
{
    static private $template = '<li> 
            <p>{{ Username }}</p> 
            <p>{{ Nome }}</p> 
            <p>{{ Cognome }}</p> 
            <a href="utenti/utente?username={{ Username }}"> visualizza dettaglio </a> 
        </li>';

    public function render($values)
    {
        $item = str_replace('{{ Username }}', $values["Username"], self::$template);
        $item = str_replace('{{ Nome }}', $values["Nome"], $item);
        $item = str_replace('{{ Cognome }}', $values["Cognome"], $item);
        return $item;
    }
}

class VisualizzazioneUtentiPage extends Page
{
    private string $ruolo;
    private string $username;
    private string $nome;
    private string $cognome;
    private string $error;
    // public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];

    public function __construct(string $ruolo = "", string $username = "", string $nome = "", string $cognome = "", string $error = "")
    {
        parent::__construct();
        parent::setTitle('Viualizzazione Utenti');
        parent::setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords([""]);
        $this->setPath('utenti');

        $this->ruolo = $ruolo;
        $this->username = $username;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->error = $error;
    }

    // private function filtra_per_ora($prenotazioni, $filtered, $data_inizio, $data_fine) {

    // }

    // private function filtra_spazi($tipo, $data_inizio, $data_fine) {

    // }

    private function filtra_utenti($dictionary)
    {
        $model_utente = new Utente();
        $result = $model_utente->prendi_parametrizzata($dictionary);
        return $result;
    }

    public function render()
    {
        $content = parent::render();
        $intestazione_pagina = $this->getContent('lista_utenti');

        // Renderizzare i filtri attivi se ce ne sono. 
        $dictionary = [];
        if ($this->ruolo != "") {
            $repl = "{{ checked-" . $this->ruolo . " }}";
            $intestazione_pagina = str_replace($repl, "checked", $intestazione_pagina);
            array_push($dictionary, ["Table" => "Ruolo", "Value" => $this->ruolo]);
        }
        if ($this->username != "") {
            $intestazione_pagina = str_replace("{{ username }}", 'value="' . $this->username . '"', $intestazione_pagina);
            array_push($dictionary, ["Table" => "Username", "Value" => $this->username]);
        }
        if ($this->nome != "") {
            $intestazione_pagina = str_replace("{{ nome }}", 'value="' . $this->nome . '"', $intestazione_pagina);
            array_push($dictionary, ["Table" => "Nome", "Value" => $this->nome]);
        }
        if ($this->cognome != "") {
            $intestazione_pagina = str_replace("{{ cognome }}", 'value="' . $this->cognome . '"', $intestazione_pagina);
            array_push($dictionary, ["Table" => "Cognome", "Value" => $this->cognome]);
        }

        // lista degli utenti 
        $query_result = $this->filtra_utenti($dictionary);

        if ($query_result) {
            $lista_utenti = "";
            $utenteItem = new UtenteItem();
            for ($i = 0; $i < count($query_result); $i++) {
                $values = [];
                $values["Username"] = $query_result[$i]["Username"];
                $values["Nome"] = $query_result[$i]["Nome"];
                $values["Cognome"] = $query_result[$i]["Cognome"];
                $lista_utenti = $lista_utenti . $utenteItem->render($values);
            }

            $intestazione_pagina = str_replace("{{ lista }}", $lista_utenti, $intestazione_pagina);
            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
        } else {
            $messaggio = " <p> Non sono stati trovai degli utenti corrispondenti ai parametri della ricerca <p>";
            $intestazione_pagina = str_replace("{{ lista }}", $messaggio, $intestazione_pagina);
            $content = str_replace("{{ content }}", $intestazione_pagina, $content);
        }

        if ($this->error) {
            $content = str_replace("{{ error }}", $this->error($this->error), $content);            // idem as above
        } else {
            $content = str_replace("{{ error }}", '', $content);            // todo: check if and why this is needed
        }
        return $content;
    }
}
