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

    private function initEvaluerUneEval() {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $rendus = $this->model->getRendusProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesRendusProfByPersonne($_SESSION['idUtilisateur']);
            $flag = 0;
            $infoTitre = [];
            $idSAE = null;
            // Vérification si l'évaluation existe
            foreach ($notes as $note) {
                if ($_GET['eval'] == $note['idEval']) {
                    $infoTitre['SAE_nom'] = $note['SAE_nom'];
                    $infoTitre['Rendu_nom'] = $note['Rendu_nom'];
                    $infoTitre['Eval_nom'] = $note['Eval_nom'];
                    $idSAE = $note['idSAE']; // Récupération de l'idSAE
                    $flag = 1;
                    break;
                }
            }
    
            if ($flag === 0 || $idSAE === null) {
                $this->initRendus();
                return;
            }
    
            // Récupération des données nécessaires
            $notesDesElvesParGroupe = $this->model->getNotesParGroupeDuneEval($_GET['eval']);
            $tousLesElevesParGroupe = $this->model->getElevesParGroupe($idSAE);
            $tousLesElevesSansGroupe = $this->model->getElevesSansGroupe($idSAE);
    
            // Regroupement des étudiants par groupe
            $tousLesGroupes = [];
            foreach ($tousLesElevesParGroupe as $eleve) {
                $idGroupe = $eleve['idGroupe'] ?? 'Sans groupe';
                if (!isset($tousLesGroupes[$idGroupe])) {
                    $tousLesGroupes[$idGroupe] = [
                        'nom' => $eleve['Groupe_nom'] ?? 'Sans groupe',
                        'etudiants' => []
                    ];
                }
                $tousLesGroupes[$idGroupe]['etudiants'][] = $eleve;
            }
    
            // Appel à la vue
            $this->view->initEvaluerPage(
                $rendus,
                $notes,
                $infoTitre,
                $notesDesElvesParGroupe,
                $tousLesGroupes,
                $tousLesElevesSansGroupe
            );
        } else { // Est un étudiant
            $this->initRendus();
        }
    }
    
    
}
