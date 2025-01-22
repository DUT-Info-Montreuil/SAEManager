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
    private function initAjouterUneNote(){
        if ($_SESSION["estProfUtilisateur"] == 1) { //Est un prof
            $idRendu = $_POST['idRendu'];
            $test = $this->model->creerNotePourUnRendu($idRendu);
            header('Location: index.php?module=rendus&action=home');
        }else{
            $this->initRendus();
        }
    }

    private function initEvaluerUneEval() {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un prof
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
                    $infoTitre['idEval'] = $note['idEval'];
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

    private function initMettreAJourLesNotes() {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $notes = [];
            foreach ($_POST as $key => $value) {
                // Vérifie si la clé commence par 'note_idEleve_'
                if (strpos($key, 'note_idEleve_') === 0) {
                    // Extrait l'ID de l'élève à partir de la clé (la partie numérique après 'note_idEleve_')
                    $idEleve = substr($key, strlen('note_idEleve_'));
    
                    // Récupère la note associée à cet élève
                    $note = isset($_POST['note_idEleve_'.$idEleve]) ? $_POST['note_idEleve_'.$idEleve] : '';
    
                    // Ajoute les données dans le tableau $notes
                    $notes[] = [
                        'idEleve' => $idEleve,
                        'idEval' => $_POST['idEval'],
                        'note' => $note
                    ];
                }
            }
    
            $this->model->MettreAJourLesNotes($notes);
            header('Location: index.php?module=rendus&action=home');
        } else { // Est un étudiant
            $this->initRendus();
        }
    }
    

    private function validerModifHomePage(){
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un professeur
            $idEval = $_POST['idEval'];
            $noteNom = $_POST['noteNom'];
            $coef = $_POST['coef'];
            $this->model->MettreAJourInfoUneEval($idEval, $noteNom, $coef);
            header('Location: index.php?module=rendus&action=home');

            
        }else { // Est un étudiant
            $this->initRendus();
        }
    }
    
}
