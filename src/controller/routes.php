<?php

$project_root = dirname(__FILE__, 2);
include_once 'test.php';
include_once 'router.php';
include_once 'static.php';
include_once 'page/aboutUsPage.php';
include_once 'page/homePage.php';
include_once 'page/loginPage.php';
include_once 'controller/login.php';
include_once 'page/newSpacePage.php';
include_once 'controller/new_space.php';

$about_us = new AboutUsPage();
$homepage = new HomePage();
$login = new Login();
$loginPage = new LoginPage();
$newSpacePage = new NewSpacePage();
$newSpace = new NewSpace();

$router = new Router();

$router->add(new Test());
$router->add(new StaticPage('about_us', $about_us));
$router->add(new StaticPage('', $homepage));
$router->add(new StaticPage('login', $loginPage));
$router->add($login);
$router->add(new StaticPage('spazi/nuovo', $newSpacePage));
$router->add($newSpace);

