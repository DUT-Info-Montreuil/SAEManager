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
                    $_SESSION['connexion_reussie'] = true;
                    header('Location: index.php?module=dashboard&action=home');
                } else {
                    $this->msg_erreur = "Identifiant ou mot de passe incorrect !";
                    $this->view->connexionPage($this->msg_erreur);
                }
                break;

            case "register":
                $this->view->inscriptionPage($this->msg_erreur);
                break;

            case "essaieInscription":
                $this->essaieInscription();
                break;
        }
    }

    private function essaieInscription()
    {
        try {
            $nom = $_POST['nom'] ?? null;
            $prenom = $_POST['prenom'] ?? null;
            $emailPart1 = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$nom || !$prenom || !$emailPart1 || !$password) {
                throw new Exception("Tous les champs doivent être remplis.");
            }

            if (strpos($emailPart1, '@') === false) {
                $email = $emailPart1 . '@iut.univ-paris8.fr';
            } else {
                $email = $emailPart1;
            }

            $register = $this->model->inscription($nom, $prenom, $email, $password);

            if ($register) {
                $login = $this->model->getLoginByEmail($email);

                if ($login) {
                    $_SESSION['inscription_reussie'] = true;
                    $_SESSION['identifiant_inscription'] = $login;
                    header('Location: index.php?module=connexion');
                } else {
                    throw new Exception("Erreur lors de la récupération du login.");
                }
            } else {
                throw new Exception("Erreur lors de l'inscription.");
            }
        } catch (Exception $e) {
            $this->msg_erreur = $e->getMessage();
            $this->view->inscriptionPage($this->msg_erreur);
        }
    }
}
