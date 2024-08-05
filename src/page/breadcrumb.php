<?php

include_once 'referenceList.php';

class Breadcrumb extends ReferenceList
{
    private $title;

    public function __construct($items, $title)
    {
        parent::__construct($items);
        $this->title = $title;
    }

    public function render()
    {
        $content = '<li>Ti trovi in:</li>';

        $breadcrumbItems = parent::render(). PHP_EOL;

        if (!empty($breadcrumbItems)) {
            $content .= $breadcrumbItems;
        }

        $content .= '<li id="breadcrumb-last">' . $this->title . '</li>';
        return $content;
    }
}
