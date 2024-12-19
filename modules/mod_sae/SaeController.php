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
        $saes = $this->model->getSaes();

        $lines = "";
        foreach ($saes as $sae) {
            $lines .= $this->view->lineSae($sae['name'], $sae['id']);
        }

        $this->view->initSaePage($lines);
    }

    private function initDetails()
    {
        $id = $_GET['idsae'];
        $saeRessource = $this->model->getSaeResources($id);
        $lineRessource = "";
        foreach ($saeRessource as $saeR) {
            $lineRessource .= $this->view->lineRessource($saeR['title'], $saeR['link']);
        }

        $saeRendus = $this->model->getSaeRendus($id);
        $lineRendu = "";
        foreach ($saeRendus as $saeR) {
            $lineRendu .= $this->view->lineRendus($saeR['title'], $saeR['status']);
        }

        $saeSoutenance = $this->model->getSaeSoutenances($id);
        $lineSoutenance = "";
        foreach ($saeSoutenance as $saeS) {
            $lineSoutenance .= $this->view->lineRendus($saeS['title'], $saeS['date'], $saeS['room']);
        }

        $this->view->initSaeDetails($lineRessource, $lineRendu, $lineSoutenance);
    }
}
