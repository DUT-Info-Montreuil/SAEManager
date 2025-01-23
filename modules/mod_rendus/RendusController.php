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
            case "homeMaj":
                $this->validerModifHomePage();
                break;
            case "AjouterUneNote":
                $this->initAjouterUneNote();
                break;
            case "evaluer":
                $this->initEvaluerUneEval();
                break;
            case "maj":
                $this->initMettreAJourLesNotes();
                break;
            case "supprimerEval":
                $this->supprimerEval();
                break;
            case "confirmationSupprimerEval":
                $this->confirmationSupprimerEval();
                break;
            default :
                $this->initRendus();
                break;
        }
        $this->view->initScript();
    }

    private function initRendus(){
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $rendus = $this->model->getRendusProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesRendusProfByPersonne($_SESSION['idUtilisateur']);
            $intervenants = $this->model->getAllIntervenantbyAllSaebyProf($_SESSION['idUtilisateur']);
           
        }else{
            $rendus = $this->model->getRendusByPersonne($_SESSION['idUtilisateur']);
            $notes = "";
            $intervenants = "";
        }
        $this->view->initRendusPage($rendus,$notes,$intervenants);
        
    }
    private function initAjouterUneNote(){
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $idRendu = $_POST['idRendu'];

            $this->model->creerNotePourUnRendu($idRendu);

            header('Location: index.php?module=rendus&action=home');
        }else{
            $this->initRendus();
        }
    }

    private function initEvaluerUneEval() {
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $rendus = $this->model->getRendusProfByPersonne($_SESSION['idUtilisateur']);
            $notes = $this->model->getNotesdesRendusProfByPersonne($_SESSION['idUtilisateur']);
            $flag = 0;
            $infoTitre = [];
            $idSAE = null;

            foreach ($notes as $note) {
                if ($_GET['eval'] == $note['idEval']) {
                    $infoTitre['SAE_nom'] = $note['SAE_nom'];
                    $infoTitre['Rendu_nom'] = $note['Rendu_nom'];
                    $infoTitre['Eval_nom'] = $note['Eval_nom'];
                    $infoTitre['idEval'] = $note['idEval'];
                    $idSAE = $note['idSAE'];
                    $flag = 1;
                    break;
                }
            }
    
            if ($flag === 0 || $idSAE === null) {
                $this->initRendus();
                return;
            }
    

            $notesDesElvesParGroupe = $this->model->getNotesParGroupeDuneEval($_GET['eval']);
            $tousLesElevesParGroupe = $this->model->getElevesParGroupe($idSAE);
            $tousLesElevesSansGroupe = $this->model->getElevesSansGroupe($idSAE);
    

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
    

            $this->view->initEvaluerPage(
                $rendus,
                $notes,
                $infoTitre,
                $notesDesElvesParGroupe,
                $tousLesGroupes,
                $tousLesElevesSansGroupe
            );
        } else {
            $this->initRendus();
        }
    }

    private function initMettreAJourLesNotes() {
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $notes = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'note_idEleve_') === 0) {
                    $idEleve = substr($key, strlen('note_idEleve_'));
                    $note = isset($_POST['note_idEleve_'.$idEleve]) ? $_POST['note_idEleve_'.$idEleve] : '';
                    if ($note <0 || $note > 20) {
                        $note = null;
                    }
                    $notes[] = [
                        'idEleve' => $idEleve,
                        'idEval' => $_POST['idEval'],
                        'note' => $note
                    ];
                }
            }
    
            $this->model->MettreAJourLesNotes($notes);
            header('Location: index.php?module=rendus&action=home');
        } else {
            $this->initRendus();
        }
    }
    

    private function validerModifHomePage(){
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $idEval = $_POST['idEval'];
            $noteNom = $_POST['noteNom'];
            $coef = $_POST['coef'];
            if($coef <0 || $coef > 100){
                $coef = 1;
            }
            if($noteNom == ""){
                $noteNom = "nom à définir";
            }
            $idIntervenants = isset($_POST['intervenant']) ? $_POST['intervenant'] : [];
            $this->model->MettreAJourInfoUneEval($idEval, $noteNom, $coef,$idIntervenants);
            header('Location: index.php?module=rendus&action=home');

            
        }else { // Est un étudiant
            $this->initRendus();
        }
    }

    function supprimerEval(){
        if ($_SESSION["estProfUtilisateur"] == 1) {
            $idEval = $_GET['idEval'];
            $this->view->initSupprimerEval($idEval);
        } else { // Est un étudiant
            $this->initRendus();
        }
    }

    function confirmationSupprimerEval(){
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $idEval = $_GET['idEval'];
            $this->model->supprimerEval($idEval);
            header('Location: index.php?module=rendus&action=home');
        } else { // Est un étudiant
            $this->initRendus();
        }
    }
    
}
