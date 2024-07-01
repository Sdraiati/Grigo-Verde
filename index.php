<?php

session_start();

$logged = isset($_COOKIE["LogIn"]);
if ($logged) {
    $_SESSION["LogIn"] = $_COOKIE["LogIn"];
}

echo "Hello World!";
