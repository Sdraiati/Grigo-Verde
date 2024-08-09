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
include_once 'controller/spazio.php';
include_once 'page/prenotazioneFormPage.php';
include_once 'controller/reservation_new.php';
include_once 'controller/reservation_detail.php';
include_once 'controller/reservation_update_get.php';
include_once 'controller/reservation_update_post.php';
include_once 'controller/reservation_delete.php';
include_once 'page/newUserPage.php';
include_once 'controller/new_user.php';
include_once 'page/editUserPage.php';
include_once 'controller/edit_user.php';
include_once 'controller/logout.php';
include_once 'page/editPasswordPage.php';
include_once 'controller/edit_password.php';
include_once 'page/dettaglioUtentePage.php';
include_once 'controller/reservations.php';

$about_us = new AboutUsPage();
$homepage = new HomePage();
$login = new Login();
$spazio_endpoint = new SpazioEndpoint();
$loginPage = new LoginPage();
$newSpacePage = new NewSpacePage();
$newSpace = new NewSpace();
$dettaglioSpazioPage = new DettaglioSpazioPage();
$dettaglioSpazio = new DettaglioSpazio();
$editSpacePage = new EditSpacePage();
$editSpace = new EditSpace();
$newPrenotazionePage = new PrenotazioneFormPage();
$newUserPage = new newUserPage();
$newUser = new NewUser();
$editUserPage = new EditUserPage();
$editUser = new EditUser();
$logout = new Logout();
$editPasswordPage = new EditPasswordPage();
$editPassword = new EditPassword();
$dettaglioUtentePage = new DettaglioUtentePage();


$router = new Router();

$router->add(new Test());
$router->add(new StaticPage('about_us', $about_us));
$router->add(new StaticPage('', $homepage));
$router->add(new StaticPage('login', $loginPage));
$router->add($login);
$router->add($spazio_endpoint);
$router->add(new StaticPage('spazi/nuovo', $newSpacePage));
$router->add($newSpace);
//$router->add(new StaticPage('spazi/spazio', $dettaglioSpazioPage));
$router->add($dettaglioSpazio);
$router->add(new StaticPage('spazi/modifica', $editSpacePage));
$router->add($editSpace);
$router->add(new StaticPage('dashboard/nuova-prenotazione', $newPrenotazionePage));
$router->add(new ReservationNew());
$router->add(new ReservationDetail());
$router->add(new ReservationUpdateGet());
$router->add(new ReservationUpdatePost());
$router->add(new ReservationDelete());
$router->add(new StaticPage('utenti/nuovo', $newUserPage));
$router->add($newUser);
$router->add(new StaticPage('utenti/modifica', $editUserPage));
$router->add($editUser);
$router->add($logout);
$router->add(new StaticPage('dashboard/modifica-password', $editPasswordPage));
$router->add($editPassword);
$router->add(new StaticPage('utenti/utente', $dettaglioUtentePage));
$router->add(new Reservations());

