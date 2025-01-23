<?php

require_once 'modules/mod_dashboard/DashboardView.php';
require_once 'modules/mod_dashboard/DashboardModel.php';

class DashboardController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new DashboardView();
        $this->model = new DashboardModel();
    }

    public function exec()
    {
        if (isset($_GET['action']))
            switch ($_GET['action']) {
                case "home": {
                        if ($_SESSION['estProfUtilisateur'] != 1) {
                            $listeRendu = $this->model->getRenduNonRenduPersonne($_SESSION['idUtilisateur']);
                            $listeSoutenance = $this->model->getSoutenanceNonPasserPersonne($_SESSION['idUtilisateur']);
                            $nomUtilisateur = $this->model->getPersonne($_SESSION['idUtilisateur'])[0]['prenom'];
                            $notifications = $this->model->getNotification($_SESSION['idUtilisateur']);
                            $photoDeProfil = $this->model->getPhotoDeProfil();

                            return $this->view->initDashboardPage($listeRendu, $listeSoutenance, $notifications, $nomUtilisateur, $photoDeProfil);
                        } else {
                            $listeEvaluationEvaluateur = $this->model->getEvaluationRenduEvaluateur($_SESSION['idUtilisateur']);
                            $listeSoutenance = $this->model->getPassageSoutenanceJury($_SESSION['idUtilisateur']);
                            $nomUtilisateur = $this->model->getPersonne($_SESSION['idUtilisateur'])[0]['prenom'];
                            $notifications = $this->model->getNotification($_SESSION['idUtilisateur']);
                            $photoDeProfil = $this->model->getPhotoDeProfil();

                            return $this->view->initDashboardPage($listeEvaluationEvaluateur, $listeSoutenance, $notifications, $nomUtilisateur, $photoDeProfil);
                        }
                        break;
                    }
                case "suprimmernotif":
                    $this->suprimmernotif();
                    break;
                case "uploadProfile":
                    $this->uploadImageProfile();
                    break;
            }


        return null;
    }

    public function suprimmernotif()
    {
        $this->model->suprimmernotif($_POST['idNotification']);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function uploadImageProfile()
    {
        $idUtilisateur = isset($_SESSION['idUtilisateur']) ? $_SESSION['idUtilisateur'] : exit("idUtilisateur not set");
        $file = isset($_FILES['profileImage']) ? $_FILES['profileImage'] : exit("file not set");
        $fileName = $_FILES['profileImage']['name'];

        $this->model->uploadProfileImage($file, $idUtilisateur, $fileName);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
