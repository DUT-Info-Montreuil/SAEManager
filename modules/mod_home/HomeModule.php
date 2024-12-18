<?php

require_once 'GenericModule.php';
require_once 'modules/mod_home/HomeController.php';


class HomeModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new HomeController();
    }
}
