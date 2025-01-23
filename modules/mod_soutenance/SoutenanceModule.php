<?php

require_once 'GenericModule.php';
require_once 'modules/mod_soutenance/SoutenanceController.php';

class SoutenanceModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new SoutenanceController();
    }
}
