<?php

class ReferenceItem
{
    private $title;
    private $path;
    private $currentPath;
    private $inBreadcrumb;

    public function __construct($title, $path, $currentPath, $inBreadcrumb)
    {
        $this->title = $title;
        $this->path = $path;
        $this->currentPath = $currentPath;
        $this->inBreadcrumb = $inBreadcrumb;
    }

    private function sanitize($string)
    {
        $string = preg_replace('/<[^>]*>/', '', $string);
        if ($string === '') {
            return '';
        }
        return strtolower(str_replace([' ', '_', '/'], '', $string));
    }

    public function render()
    {
        $sanitizedPath = $this->sanitize($this->path);
        $sanitizedCurrentPath = $this->sanitize($this->currentPath);

        if (!($this->inBreadcrumb)&&$sanitizedPath === $sanitizedCurrentPath) {
            $content = '<li>';
            $content .= $this->title;
            $content .= '</li>';
            return  $content;
        } else {
            $content = '<li>';
            $content .= '<a href="/' . $this->path . '">' . $this->title . '</a>';
            $content .= '</li>';
            return  $content;
        }
    }
}

class ReferenceList
{
    private $items = [];
    private $currentPath;
    private $inBreadcrumb;

    public function __construct($items, $currentPath, $inBreadcrumb = false)
    {
        $this->currentPath = $currentPath;
        $this->inBreadcrumb = $inBreadcrumb;
        foreach ($items as $title => $path) {
            $this->addItem($title, $path);
        }
    }

    public function addItem($title, $path)
    {
        $this->items[] = new ReferenceItem($title, $path, $this->currentPath, $this->inBreadcrumb);
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
