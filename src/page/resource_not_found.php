<?php

include_once 'page.php';

class ResourceNotFoundPage extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Pagina non trovata');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords([""]);
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('404'), $content);
        $content = str_replace("{{ error }}", '', $content);
        $content = preg_replace('/<nav id="breadcrumbs">.*?<\/nav>/s', '', $content);

        return $content;
    }
}
