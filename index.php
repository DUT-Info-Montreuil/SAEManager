<?php

require_once 'Connexion.php';
require_once 'components/menu/MenuComponent.php';

Connexion::initConnexion();

$menu = new MenuComponent();

include 'template.php';

?>