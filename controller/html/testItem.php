<?php

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
        $status = $result ? 'Passed' : 'Failed';
        $statusClass = $result ? 'passed' : 'failed';

        // Return the HTML row as a string
        return "
        <tr class='{$statusClass}'>
            <td>{$this->name}</td>
            <td>{$status}</td>
        </tr>";
    }
}
