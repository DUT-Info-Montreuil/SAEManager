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
    }

    private function initRendus(){
        $listePersonne = $this->model->recupListePersonne();
        //var_dump($listePersonne);
        $this->view->initCreerSaePage($listePersonne);
    }

    private function handleFormSubmission()
    {
        // Récupérer les valeurs du formulaire
        $nomSae = $_POST['nomSae'] ?? null;
        $semestre = $_POST['semestre'] ?? null;
        $sujet = $_POST['sujet'] ?? null;
        $coResponsables = $_POST['coResponsables'] ?? [];
        $intervenants = $_POST['intervenants'] ?? [];
        $eleves = $_POST['eleves'] ?? [];

        // Envoyer les données au modèle
        $result = $this->model->createSae($nomSae, $semestre, $sujet, $coResponsables, $intervenants, $eleves);

        if ($result) {
            echo "SAE créée avec succès !";
        } else {
            echo "Erreur lors de la création de la SAE.";
        }
    }
}
