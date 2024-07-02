<?php

include_once 'test.php';
include_once 'router.php';

$router = new Router();

$router->add(new Test());
