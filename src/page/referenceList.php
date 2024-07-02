<?php

class ReferenceItem
{
    private $title;
    private $path;

    public function __construct($title, $path)
    {
        $this->title = $title;
        $this->path = $path;
    }

    public function render()
    {
        $content = '<li>';
        $content .= '<a href="/' . $this->path . '">' . $this->title . '</a>';
        $content .= '</li>';
        return $content;
    }
}

class ReferenceList
{
    private $items = [];

    public function __construct($items)
    {
        foreach ($items as $title => $path) {
            $this->addItem($title, $path);
        }
    }

    public function addItem($title, $path)
    {
        $this->items[] = new ReferenceItem($title, $path);
    }

    public function render()
    {
        $content = '';
        foreach ($this->items as $item) {
            $content .= $item->render() . PHP_EOL;
        }
        return $content;
    }
}
