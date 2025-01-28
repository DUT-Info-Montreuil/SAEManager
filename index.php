<?php

require_once 'Connexion.php';
require_once 'components/menu/NavBarComponent.php';
require_once 'modules/mod_connexion/ConnexionModule.php';
require_once 'Site.php';
include_once 'protectionCSRF.php';


session_start();

Connexion::initConnexion();
$site = new Site();
$site->execModule();

$menu = new NavBarComponent();

$module = $site->getModule()->getAffichage();
include 'template.php';



//Tout droit réservée
//All right reserved
//Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN
