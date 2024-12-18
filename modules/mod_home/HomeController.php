<?php

require_once 'modules/mod_home/HomeView.php';
require_once 'modules/mod_home/HomeModel.php';

class HomeController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new HomeView();
        $this->model = new HomeModel();
    }

    public function exec()
    {
        return $this->view->initHomePage();
    }
}
