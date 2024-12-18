<?php

require_once 'modules/mod_rendus/RendusView.php';
require_once 'modules/mod_rendus/RendusModel.php';

class RendusController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new RendusView();
        $this->model = new RendusModel();
    }

    public function exec()
    {
        return $this->view->initRendusPage();
    }
}
