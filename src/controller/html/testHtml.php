<?php

include_once 'html.php';

class TestHtml extends Html
{
    private $testItems;

    public function __construct($testItems)
    {
        $this->testItems = $testItems;
    }

    public function render($_)
    {
        $content = parent::render('test');
        $content = str_replace("{{ content }}", $this->getContent('test_page'), $content);
        $tests = '';
        foreach ($this->testItems as $testItem) {
            $tests .= $testItem->render();
        }
        $content = str_replace('{{ tests }}', $tests, $content);
        return $content;
    }
}
