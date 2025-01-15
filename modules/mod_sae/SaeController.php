<?php

require_once 'modules/mod_sae/SaeView.php';
require_once 'modules/mod_sae/SaeModel.php';

class SaeController
{

    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new SaeView();
        $this->model = new SaeModel();
    }

    public function exec()
    {
        switch ($_GET['action']) {
            case "home":
                $this->initSae();
                break;
            case "details":
                $this->initDetails();
                break;
            case "groupe":
                $this->initGroup();
                break;
            case "note":
                $this->initNote();
                break;
            case "ajout_champ":
                $this->ajout();
                break;
            case "uploadFichier":
                $this->uploadFichier();
                break;
            case "ajoutDepotRendu":
                $this->depotRendu();
                break;
            case "ajoutDepotSupport":
                break;
            case "createRendu":
                $this->createRendu();
                break;
            case "supprimerRendu":
                $this->delRendu();
                break;
            case "supprimerRessource":
                $this->delRessource();
                break;
            case "modifierRendu":
                $this->updateRendu();
                break;
            case "createSoutenance":
                $this->createSoutenance();
                break;
            case "supprimerSoutenance":
                $this->delSoutenance();
                break;
            case "modifierSoutenance":
                $this->updateSoutenance();
                break;
        }
    }

    private function initSae()
    {
        $saes = $this->model->getSaesByPersonneId($_SESSION['idUtilisateur']);
        $this->view->initSaePage($saes);
    }

    private function initDetails()
    {
        $mySAE = $this->model->getSaesByPersonneId($_SESSION['idUtilisateur']);
        $acces = false;
        foreach ($mySAE as $sae) {
            if ($sae['idSAE'] == $_GET['id']) {
                $acces = true;
            }
        }

        if ($acces) {
            $saes = $this->model->getSaeById($_GET['id']);
            $ressource = $this->model->getRessourceBySAE($_GET['id']);
            $rendus = $this->model->getRenduBySae($_GET['id']);
            $soutenance = $this->model->getSoutenanceBySae($_GET['id']);
            $champs = $this->model->getChampBySae($_GET['id']);
            $repId = $this->model->getReponseIdBySAE($_GET['id']);
            $rendusDeposer = [];
            foreach ($rendus as $rendu)
                if ($this->model->didGroupDropRendu(htmlspecialchars($rendu['idRendu']), $saes[0]['idSAE'])) {
                    $renduGroupe = $this->model->getRenduEleve($rendu['idRendu'], $saes[0]['idSAE']);
                    $rendusDeposer[htmlspecialchars($rendu['idRendu'])] = $renduGroupe[0]['dateDepot'];
                }

            $this->view->initSaeDetails($saes, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer);
        } else {
            header('Location: index.php');
        }
    }

    private function initGroup()
    {
        $sae = $this->model->getSaeById($_GET['id']);
        $groupeID = $this->model->getMyGroupId($_GET['id']);
        $groupe = $this->model->getMyGroup($_GET['id'], $groupeID);
        $responsable = $this->model->getSaeResponsable($_GET['id']);

        $this->view->initGroupPage($sae, $groupe, $responsable);
    }

    private function initNote()
    {

        $groupeID = $this->model->getMyGroupId($_GET['id']);

        $notes = $this->model->getNote($_GET['id'], $groupeID);
        $sae = $this->model->getSaeById($_GET['id']);
        $noteSoutenance = $this->model->getNoteSoutenance($_GET['id'], $groupeID);

        $this->view->initNotePage($notes, $sae, $noteSoutenance);
    }

    private function uploadFichier()
    {
        $fileName = isset($_POST['fileName']) ? $_POST["fileName"] : (isset($_FILES['fileInput']['name']) ? basename($_FILES['fileInput']['name']) : null);

        if (!isset($_FILES['fileInput'])) {
            echo "Erreur lors du téléchargement du fichier.";
            exit;
        }

        $this->model->uploadFichier($fileName, $_FILES['fileInput']['tmp_name'], $_POST['colorChoice'], $_GET["id"]);
    }

    private function depotRendu()
    {
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $idRendu = isset($_POST['idSaeDepotRendu']) ? $_POST['idSaeDepotRendu'] : exit("idRendu not set");
        $file = isset($_FILES['fileInputRendu']) ? $_FILES['fileInputRendu'] : exit("file not set");
        $fileName = $_FILES['fileInputRendu']['name'];

        $depotreussi = $this->model->uploadFileRendu($file, $idSae, $fileName, $idRendu);
        header("Location: index.php?module=sae&action=details&id=" . $idSae);
    }

    private function ajout()
    {
        $idChamp = $_GET["idchamp"];
        if (isset($_POST["reponse" . $idChamp])) {
            $reponse = $_POST["reponse" . $idChamp];

            $this->model->ajoutChamp($idChamp, $_SESSION['idUtilisateur'], $reponse);
        }
        header("Location: index.php?module=sae&action=details&id=" . $_GET['id']);
    }

    private function createRendu()
    {
        $idSae = $_GET['id'];
        $titre = $_POST['titreRendu'];
        $dateLimite = $_POST['dateLimiteRendu'];
        $estNote = $_POST['renduNote'];
        $coeff = $_POST['coeff'];

        $this->model->createRendu($titre, $dateLimite, $idSae, $estNote, $coeff);
        header("Location: index.php?module=sae&action=details&id=" . $idSae);
    }

    private function delRendu()
    {
        $idRendu = $_GET['id'];

        $this->model->delRendu($idRendu);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delRessource()
    {
        $idRessource = $_GET['idRessource'];
        $idSAE = $_GET['id'];

        $this->model->delRessourceSAE($idSAE, $idRessource);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    private function updateRendu() {
        $titre = $_POST['titreRendu'];
        $dateLimite = $_POST['dateLimiteRendu'];
        $idRendu = $_POST['idRenduAModifier'];

        $this->model->updateRendu($idRendu, $titre, $dateLimite);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function createSoutenance() {
        $idSAE = $_GET['id'];
        $titre = $_POST['titreSoutenance'];
        $date = $_POST['dateSoutenance'];
        $duree = $_POST['dureeSoutenance'];
        $salle = $_POST['salleSoutenance'];
        
        $this->model->createSoutenance($titre, $date, $salle, $duree, $idSAE);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delSoutenance() {
        $idSoutenance = $_GET['id'];

        $this->model->delSoutenance($idSoutenance);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function updateSoutenance() {
        $idSoutenance = $_POST['idSoutenanceAModifier'];
        $titre = $_POST['titreSoutenance'];
        $date = $_POST['dateSoutenance'];
        $duree = $_POST['dureeSoutenance'];
        $salle = $_POST['salleSoutenance'];

        $this->model->updateSoutenance($idSoutenance, $titre, $date, $salle, $duree);
        var_dump($_POST);
        //header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}
