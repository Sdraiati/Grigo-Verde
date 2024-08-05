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
include_once 'page/dettaglioSpazioPage.php';
include_once 'controller/dettaglioSpazio.php';
include_once 'page/editSpacePage.php';
include_once 'controller/edit_space.php';
include_once 'page/prenotazioneFormPage.php';
include_once 'controller/reservation_new.php';

$about_us = new AboutUsPage();
$homepage = new HomePage();
$login = new Login();
$loginPage = new LoginPage();
$newSpacePage = new NewSpacePage();
$newSpace = new NewSpace();
$dettaglioSpazioPage = new DettaglioSpazioPage();
$dettaglioSpazio = new DettaglioSpazio();
$editSpacePage = new EditSpacePage();
$editSpace = new EditSpace();
$newPrenotazionePage = new PrenotazioneFormPage();

$router = new Router();

$router->add(new Test());
$router->add(new StaticPage('about_us', $about_us));
$router->add(new StaticPage('', $homepage));
$router->add(new StaticPage('login', $loginPage));
$router->add($login);
$router->add(new StaticPage('spazi/nuovo', $newSpacePage));
$router->add($newSpace);
//$router->add(new StaticPage('spazi/spazio', $dettaglioSpazioPage));
$router->add($dettaglioSpazio);
$router->add(new StaticPage('spazi/modifica', $editSpacePage));
$router->add($editSpace);
$router->add(new StaticPage('dashboard/nuova-prenotazione', $newPrenotazionePage));
$router->add(new ReservationNew());
