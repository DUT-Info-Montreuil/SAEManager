<?php

require_once 'GenericModule.php';
require_once 'modules/mod_rendus/RendusController.php';

class RendusModule extends GenericModule
{

    public function __construct()
    {
        parent::__construct();
        $this->controller = new RendusController();
    }
}
