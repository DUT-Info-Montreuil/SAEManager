<?php

require_once 'GenericModule.php';
require_once 'modules/mod_dashboard/DashboardController.php';


class DashboardModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new DashboardController();
    }
}
