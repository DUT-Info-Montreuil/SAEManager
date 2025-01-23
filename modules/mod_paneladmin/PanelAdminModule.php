<?php

require_once 'GenericModule.php';
require_once 'modules/mod_paneladmin/PanelAdminController.php';

class PanelAdminModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new PanelAdminController();
    }
}
