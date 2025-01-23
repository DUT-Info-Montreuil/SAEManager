<?php

require_once 'modules/mod_paneladmin/PanelAdminView.php';
require_once 'modules/mod_paneladmin/PanelAdminModel.php';

class PanelAdminController
{
    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new PanelAdminView();
        $this->model = new PanelAdminModel();
    }

    public function exec()
    {
        switch ($_GET['action'] ?? 'home') {
            case "home":
                $this->homePanel();
                break;
            case "ajouterProfesseur":
                $this->ajouterProf();
                break;
            case "suprimmerProf":
                $this->suprimmerProf();
                break;
            default:
                $this->homePanel();
                break;

        }
    }

    private function homePanel()
    {
        $listePersonneNonProf = $this->model->getPersonneNonProf();
        $listeProf = $this->model->getPersonneProf();
        $this->view->initPanel($listePersonneNonProf, $listeProf);
    }

    private function ajouterProf()
    {
        if (isset($_POST['personnes'])) {
            $professeursCoches = $_POST['personnes'];
            $this->model->addProfesseur($professeursCoches);
        }
        header('Location:index.php?module=paneladmin&action=home');
    }

    private function suprimmerProf()
    {
        if (isset($_POST['professeurs'])) {
            $professeursCoches = $_POST['professeurs'];
            $this->model->delProfesseur($professeursCoches);
        }
        header('Location:index.php?module=paneladmin&action=home');
    }


}
