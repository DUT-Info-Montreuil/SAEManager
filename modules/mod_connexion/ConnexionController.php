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
                $this->view->deconnexionPage('<p>Vous n\'êtes pas connecté ! Pour se connecter : <a href="index.php"> Se connecter</a></p>');
                break;
            case "essaieConnexion" :
                if($this->model->essaieConnexion()){
                    header('Location: index.php?module=dashboard');
                }
                else{
                    $this->msg_erreur = "identifiant ou mot de passe incorrect !";
                    $this->view->connexionPage($this->msg_erreur);
                }
                break;
        }
    }
}


?>