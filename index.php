<?php

require_once 'Connexion.php';
require_once 'components/menu/NavBarComponent.php';
require_once 'components/card/CardComponent.php';
require_once 'modules/mod_home/HomeModule.php';


Connexion::initConnexion();

$menu = new NavBarComponent();

$home = new HomeModule();

include 'template.php';
