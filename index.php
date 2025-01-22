<?php

require_once 'Connexion.php';
require_once 'components/menu/NavBarComponent.php';
require_once 'components/footer/FooterComponent.php';
require_once 'modules/mod_connexion/ConnexionModule.php';
require_once 'Site.php';
include_once 'protectionCSRF.php';


session_start();

Connexion::initConnexion();
$site = new Site();
$site->execModule();

$menu = new NavBarComponent();
$footer = new FooterComponent();

$module = $site->getModule()->getAffichage();
include 'template.php';



