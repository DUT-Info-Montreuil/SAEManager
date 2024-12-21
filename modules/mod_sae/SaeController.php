<?php

require_once 'modules/mod_sae/SaeView.php';
require_once 'modules/mod_sae/SaeModel.php';

class SaeController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new SaeView();
        $this->model = new SaeModel();
    }

    public function exec()
    {
        switch ($_GET['action']) {
            case "home":
                $this->initSae();
                break;
            case "details":
                $this->initDetails();
                break;
        }
    }

    private function initSae()
    {

        $saes = $this->model->getSaesByPersonneId(1);
        $this->view->initSaePage($saes);
    }

    private function initDetails()
    {
        $mySAE = $this->model->getSaesByPersonneId(1);
        $acces = false;
        foreach ($mySAE as $sae) {
            if ($sae['idSAE'] == $_GET['id']) {
                $acces = true;
            }
        }

        if ($acces) {
            $saes = $this->model->getSaeById($_GET['id']);
            $ressource = $this->model->getRessourceBySAE($_GET['id']);
            $rendus = $this->model->getRenduBySae($_GET['id']);
            $soutenance = $this->model->getSoutenanceBySae($_GET['id']);

            $this->view->initSaeDetails($saes, $ressource, $rendus, $soutenance);
        } else {
            header('Location: index.php');
        }
    }
}
