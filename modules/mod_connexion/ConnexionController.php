<?php

require_once 'modules/mod_connexion/ConnexionView.php';
require_once 'modules/mod_connexion/ConnexionModel.php';

class ConnexionController
{
    private $view;
    private $model;
    private $infoConnexion;
    private $msg_erreur;

    public function __construct()
    {
        $this->infoConnexion = isset($_GET['infoConnexion']) ? $_GET['infoConnexion'] : "connexionPage";
        $this->view = new ConnexionView();
        $this->model = new ConnexionModel();
        $this->msg_erreur = "";
    }

    public function exec()
    {
        switch ($this->infoConnexion) {
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
                    // Connexion réussie
                    $_SESSION['connexion_reussie'] = true;
                    if ($_SESSION['estProfUtilisateur'] != 1) {
                        header('Location: index.php?module=dashboard');
                    } else {
                        header('Location: index.php?module=home');
                    }
                } else {
                    // Échec de la connexion
                    $this->msg_erreur = "identifiant ou mot de passe incorrect !";
                    $this->view->connexionPage($this->msg_erreur);
                }
                break;
            case "register":
                $this->view->inscriptionPage($this->msg_erreur);
        }
    }
}
