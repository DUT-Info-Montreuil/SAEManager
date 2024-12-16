<?php

require_once 'components/menu/MenuController.php';
require_once 'GenericComponent.php';

class MenuComponent extends GenericComponent {

    // Ne pas laisser public
    public $controller;

    public function __construct() {
        parent::__construct();
        $this->controller = new MenuController();
    }


}