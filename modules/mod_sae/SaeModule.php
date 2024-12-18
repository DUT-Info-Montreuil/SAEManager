<?php


require_once 'GenericModule.php';
require_once 'modules/mod_sae/SaeController.php';


class SaeModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new SaeController();
    }
}
