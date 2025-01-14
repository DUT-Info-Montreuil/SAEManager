<?php

require_once 'Connexion.php';
require_once 'components/menu/NavBarComponent.php';
require_once 'modules/mod_home/HomeModule.php';
require_once 'Site.php';


Connexion::initConnexion();
$site = new Site();
$site->execModule();

$menu = new NavBarComponent();

$module = $site->getModule()->getAffichage();
include 'template.php';




