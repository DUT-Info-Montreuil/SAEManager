<?php

require_once 'modules/mod_connexion/ConnexionView.php';
require_once 'modules/mod_connexion/ConnexionModel.php';

class ConnexionController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new ConnexionView();
        $this->model = new ConnexionModel();
    }

    public function exec()
    {
        return $this->view->initConnexionPage();
    }
}


?>