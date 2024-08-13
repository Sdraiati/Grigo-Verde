<?php

include_once 'page.php';

class TestItem
{
    private $fn;
    private $name;

    public function __construct($test_name, $test_fn)
    {
        $this->fn = $test_fn;
        $this->name = $test_name;
    }

    public function render()
    {
        // Execute the test function
        $result = call_user_func($this->fn);

        // Determine if the test passed or failed
        $status = $result ? 'Passato' : 'Fallito';
        $statusClass = $result ? 'passed' : 'failed';

        // Return the HTML row as a string
        return "
        <tr class='{$statusClass}'>
            <td>{$this->name}</td>
            <td>{$status}</td>
        </tr>";
    }
}

class TestPage extends Page
{
    private $testItems;

    public function __construct()
    {
        parent::__construct();
        $this->setTitle('Test');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->setPath('/test');
        $this->addKeywords([""]);

        $this->testItems = [];
    }

    public function addTest($test_name, $test_fn)
    {
        $testItem = new TestItem($test_name, $test_fn);
        array_push($this->testItems, $testItem);
    }

    public function addTests($tests)
    {
        foreach ($tests as $test_name => $test_fn) {
            $this->addTest($test_name, $test_fn);
        }
        return $this;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('test_page'), $content);
        $tests = '';
        foreach ($this->testItems as $testItem) {
            $tests .= $testItem->render();
        }
        $content = str_replace('{{ tests }}', $tests, $content);
        return $content;
    }
}
