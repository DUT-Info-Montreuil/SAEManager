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
                $this->view->deconnexionPage();
                $this->model->deconnexion();        
                break;
            case "essaieConnexion" :
                if($this->model->essaieConnexion()){ //connexion réussite
                    header('Location: index.php?module=home');
                    //require_once 'modules/mod_home/HomeModule.php';
                }
                else{ //erreur connexion
                    $this->msg_erreur = "identifiant ou mot de passe incorrect !";
                    $this->view->connexionPage($this->msg_erreur);
                }
                break;
        }
    }
}


?>