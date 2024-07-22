<?php

$project_root = dirname(__FILE__, 2);
include_once 'test.php';
include_once 'router.php';
include_once 'static.php';
include_once 'page/aboutUsPage.php';
include_once 'page/homePage.php';

$about_us = new AboutUsPage();
$homepage = new HomePage();

$router = new Router();

$router->add(new Test());
$router->add(new StaticPage('about_us', $about_us));
$router->add(new StaticPage('', $homepage));
