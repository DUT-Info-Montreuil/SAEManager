<?php

require_once 'Connexion.php';
require_once 'components/menu/NavBarComponent.php';

Connexion::initConnexion();

$menu = new NavBarComponent();

include 'template.php';

?>