<?php

require_once 'GenericModule.php';
require_once 'modules/mod_creerSae/CreerSaeController.php';


class CreerSaeModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new CreerSaeController();
    }
}
