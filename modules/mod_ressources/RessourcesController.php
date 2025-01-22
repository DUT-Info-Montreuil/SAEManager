<?php

require_once 'modules/mod_ressources/RessourcesView.php';
require_once 'modules/mod_ressources/RessourcesModel.php';

class RessourcesController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new RessourcesView();
        $this->model = new RessourcesModel();
    }

    public function exec()
    {

        switch ($_GET['action']) {
            case "home":
                $this->initRessource();
                break;
        }
    }

    private function initRessource()
    {

        $ressource = $this->model->getRessource();
        $myRessources = $this->model->getRessourceInscrit();
        $this->view->initRessources($ressource, $myRessources);
    }
}
