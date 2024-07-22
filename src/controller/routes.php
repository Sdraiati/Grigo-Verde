<?php

$project_root = dirname(__FILE__, 2);
include_once 'test.php';
include_once 'router.php';
include_once 'static.php';
include_once 'page/about_us.php';

$about_us = new AboutUsPage();

$router = new Router();

$router->add(new Test());
$router->add(new StaticPage('about_us', $about_us));
