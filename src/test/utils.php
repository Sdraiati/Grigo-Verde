<?php

function contiene($array, $elemento)
{
    foreach ($array as $el) {
        if ($el == $elemento) {
            return True;
        }
    }
    return False;
}
