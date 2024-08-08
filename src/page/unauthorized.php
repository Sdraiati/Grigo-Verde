<?php

include_once 'page.php';

class UnauthorizedPage extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Non autorizzato');
        $this->setBreadcrumb([]);
        $this->addKeywords([""]);
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('403'), $content);
        $content = str_replace("{{ error }}", '', $content);
        $content = preg_replace('/<nav id="breadcrumbs">.*?<\/nav>/s', '', $content);

        return $content;
    }
}
