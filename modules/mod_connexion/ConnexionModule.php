<?php

require_once 'GenericModule.php';
require_once 'modules/mod_connexion/ConnexionController.php';


class ConnexionModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new ConnexionController();
    }
}
