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
        switch ($_GET['action']) {
            case "home":
                $this->initRendus();
                break;
            case "evaluer":
                $this->initEvaluerUneEval();
                break;
            default :
                $this->initRendus();
                break;
        }
    }

    private function initRendus(){
        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            $rendus = $this->model->getRendusProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesRendusProfByPersonne($_SESSION['idUtilisateur']);
        }else{
            $rendus = $this->model->getRendusByPersonne($_SESSION['idUtilisateur']);
            $notes = "";
        }
        $this->view->initRendusPage($rendus,$notes);
    }

    private function initEvaluerUneEval(){
        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            $rendus = $this->model->getRendusProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesRendusProfByPersonne($_SESSION['idUtilisateur']);
            $flag = 0;
            foreach ($notes as $note) {
                if($_GET['eval'] == $note['idEval']){
                    $infoTitre['SAE_nom'] = $note['SAE_nom'];
                    $infoTitre['Rendu_nom'] = $note['Rendu_nom'];
                    $infoTitre['Eval_nom'] = $note['Eval_nom'];
                    $flag = 1;
                }
            }
            if($flag === 0){
                $this->initRendus();
            }else{
                $this->view->initEvaluerPage($infoTitre);
            }

        }else{
            $this->initRendus();
        }
    }
}
