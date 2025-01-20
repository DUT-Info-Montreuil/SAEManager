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
        if(isset($_GET['action']))
            switch($_GET['action']){
                case "suprimmernotif":
                    $this->suprimmernotif();
                    break;
            }

        if($_SESSION['estProfUtilisateur']!=1) {
            $listeRendu = $this->model->getRenduNonRenduPersonne($_SESSION['idUtilisateur']);
            $listeSoutenance = $this->model->getSoutenanceNonPasserPersonne($_SESSION['idUtilisateur']);
            $nomUtilisateur = $this->model->getPersonne($_SESSION['idUtilisateur'])[0]['prenom'];
            $notifications = $this->model->getNotification($_SESSION['idUtilisateur']);

            return $this->view->initDashboardPage($listeRendu, $listeSoutenance, $notifications, $nomUtilisateur);
        }
        else{
            $listeEvaluationEvaluateur = $this->model->getEvaluationRenduEvaluateur($_SESSION['idUtilisateur']);
        
            $listeSoutenance = $this->model->getEvaluationSoutenanceEvaluateur($_SESSION['idUtilisateur']);
        
            $nomUtilisateur = $this->model->getPersonne($_SESSION['idUtilisateur'])[0]['prenom'];
            $notifications = $this->model->getNotification($_SESSION['idUtilisateur']);

            return $this->view->initDashboardPage($listeEvaluationEvaluateur, $listeSoutenance, $notifications, $nomUtilisateur);
        }
        return null;
    }

    public function suprimmernotif(){
        $this->model->suprimmernotif($_POST['idNotification']);
    }
}
