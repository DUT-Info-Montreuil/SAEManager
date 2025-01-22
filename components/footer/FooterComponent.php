<?php

require_once 'components/footer/FooterController.php';
require_once 'GenericComponent.php';


class FooterComponent extends GenericComponent{

    protected $controller;

    public function __construct()
    {
        parent::__construct();
        $this->controller = new FooterController();
    }

}