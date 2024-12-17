<?php

require_once 'components/menu/NavBarController.php';
require_once 'GenericComponent.php';

class NavBarComponent extends GenericComponent {

    // Ne pas laisser public
    public $controller;

    public function __construct() {
        parent::__construct();
        $this->controller = new NavBarController();
    }


}