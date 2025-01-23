<?php

require_once 'modules/mod_connexion/ConnexionView.php';
require_once 'modules/mod_connexion/ConnexionModel.php';

class ConnexionController
{

    private $view;
    private $model;
    private $infoConnexion;
    private $msg_erreur;


    public function __construct(){
        $this->infoConnexion = isset($_GET['infoConnexion']) ? $_GET['infoConnexion'] : "connexionPage";

        $this->view = new ConnexionView();
        $this->model = new ConnexionModel();
        $this->msg_erreur="";
    }

    public function exec(){

        switch($this->infoConnexion){
            case "connexionPage":
                $this->view->connexionPage($this->msg_erreur);
                break;

            case "deconnexion":
                $this->model->deconnexion();
                $_SESSION['deconnexion'] = true;
                header("Location: index.php?module=home");
                break;

            case "essaieConnexion":
                if ($this->model->essaieConnexion()) {
                    $_SESSION['connexion_reussie'] = true;
                    header('Location: index.php?module=dashboard&action=home');
                } else {
                    $this->msg_erreur = "identifiant ou mot de passe incorrect !";
                    $this->view->connexionPage($this->msg_erreur);
                }
                break;
            case "register":
                $this->view->inscriptionPage($this->msg_erreur);
                break;
            case "essaieInscription":
                $this->essaieInscription();
                if ($_SESSION) {
                    header('Location: index.php?module=dashboard&action=home');
                }
                break;
        }
    }

    private function essaieInscription()
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $emailPart1 = $_POST['email'];
        $password = $_POST['password'];

        if (strpos($emailPart1, '@') === false) {
            $email = $emailPart1 . '@iut.univ-paris8.fr';
        }

        $inscription = $this->model->inscription($nom, $prenom, $email, $password);

        if ($inscription) {
            $_SESSION['inscription_reussie'] = true;
            $_SESSION['identifiant_inscription'] = strtolower($prenom) . '.' . strtolower($nom);
            header('Location: index.php?module=connexion');
        }
    }
}
