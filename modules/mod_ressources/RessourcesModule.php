<?php

require_once 'GenericModule.php';
require_once 'modules/mod_ressources/RessourcesController.php';

class RessourcesModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new RessourcesController();
    }
}
