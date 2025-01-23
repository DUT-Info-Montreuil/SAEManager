<?php

require_once 'modules/mod_creerSae/CreerSaeView.php';
require_once 'modules/mod_creerSae/CreerSaeModel.php';

class CreerSaeController
{
    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new CreerSaeView();
        $this->model = new CreerSaeModel();
    }

    public function exec()
    {
        switch ($_GET['action'] ?? 'home') {
            case "home":
                $this->initRendus();
                break;
            case "submit":
                $this->handleFormSubmission();
                break;
            default:
                echo "Oups, il y a un problème...";
                break;
        }
        $this->view->initScript();
    }

    private function initRendus($msg = null){
        $listePersonne = $this->model->recupListePersonneSansMoi();
        $this->view->initCreerSaePage($listePersonne,$msg);
    }

    private function handleFormSubmission(){
        $nomSae = $_POST['nomSae'] ?? null;
        $semestre = $_POST['semestre'] ?? null;
        $sujet = $_POST['sujet'] ?? null;
        $coResponsables = $_POST['coResponsables'] ?? [];
        $intervenants = $_POST['intervenants'] ?? [];
        $eleves = $_POST['eleves'] ?? [];

        foreach($coResponsables as $respo){
            foreach($intervenants as $interv){
                if($respo == $interv){
                    $msg = "Un intervenant ne peut pas être co-responsable.";
                    $this->initRendus($msg);
                    return;
                }
            }
        }

        $result = $this->model->createSae($nomSae, $semestre, $sujet, $coResponsables, $intervenants, $eleves);

        if ($result) {
            header("Location: index.php?module=sae&action=home");
        } else {
            echo "Erreur lors de la création de la SAE.";
        }
    }
}
