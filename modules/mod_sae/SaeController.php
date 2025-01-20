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
            case "input_champ":
                $this->inputChamp();
                break;
            case "uploadFichier":
                $this->uploadFichier();
                break;
            case "ajoutProf":
                $this->ajoutProf();
                break;
            case "ajoutDepotRendu":
                $this->depotRendu();
                break;
            case "ajoutDepotSupport":
                $this->depotSupport();
                break;    
            case "suprimmerDepotGroupeRendu":
                $this->suprimmerDepotRenduGroupe();
                break;
            case "suprimmerDepotGroupeSupport":
                $this->suprimmerDepotSupportGroupe();
                break;
            case "soutenance":
                $this->initSoutenance();
                break;
            case "calendrierPassageSoutenance":
                $this->initPageSoutenanceCalendrier();
                break;
            case "placerPassageSoutenance":
                $this->placerPassageSoutenance();
                break;
            case "createRendu":
                $this->createRendu();
                break;
            case "supprimerRendu":
                $this->delRendu();
                break;
            case "supprimerRessource":
                $this->delRessourceSAE();
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
            case "createChamp":
                $this->createChamp();
                break;
            case "modifierSujet":
                $this->updateSujet();
                break;
            case "supprimerSujet":
                $this->delSujet();
                break;
            case "createRessource":
                $this->createRessource();
                break;
            case "ajouterRessource":
                $this->addRessource();
                break;
            case "supprimerChamp":
                $this->delChamps();
                break;
            case "ajout_champ":
                $this->ajout();
                break;
            case "creation_groupe":
                $this->creerPropositionGroupe();
                break;
            case "gererGroupe":
                $this->gererGroupe();
                break;
            case "deposerFichierRendu":
                $this->deposerFichierRendu();
                break;
            case "ressources":
                $this->initRessource();
                break;
            case "depotRessource":
                $this->deposerFichierRessource();
                break;
            case "delRessource":
                $this->delRessource();
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
            $soutenances = $this->model->getSoutenanceBySae($_GET['id']);
            $champs = $this->model->getChampBySae($_GET['id']);
            $repId = $this->model->getReponseIdBySAE($_GET['id']);
            $allRessource = $this->model->getRessource();

            $rendusDeposer = [];

            foreach ($rendus as $rendu){
                if ($this->model->didGroupDropRendu(htmlspecialchars($rendu['idRendu']), $saes[0]['idSAE'])) {
                    $renduGroupe = $this->model->getRenduEleve($rendu['idRendu'], $saes[0]['idSAE']);
                    $rendusDeposer[htmlspecialchars($rendu['idRendu'])] = $renduGroupe[0]['dateDepot'];
                }
                if($this->model->didGroupDropRendu(htmlspecialchars($rendu['idRendu']), $saes[0]['idSAE'])){
                    $renduGroupe = $this->model->getRenduEleve($rendu['idRendu'], $saes[0]['idSAE']);
                    $rendusDeposer[htmlspecialchars($rendu['idRendu'])] = $renduGroupe[0]['dateDepot'];
                }
            }
            $supportsDeposer = [];
            foreach($soutenances as $soutenance){
                if($this->model->didGroupeDropSupport(htmlspecialchars($soutenance['idSoutenance']), htmlspecialchars($saes[0]['idSAE']))){
                    $supportGroup = $this->model->getSupportEleve($soutenance['idSoutenance'], $saes[0]['idSAE']);
                    $supportsDeposer[htmlspecialchars($supportGroup[0]['idSoutenance'])] = $supportGroup[0]['support'];
                }
            }
            $profs = $this->model->getProfsBySAE($_GET['id']);
            $etudiants = $this->model->getEtudiantsBySAE($_GET['id']);
            $inGroupeSAE = $this->model->inGroupeBySAE($_GET['id']);
            $proposition = $this->model->inPropositions($_GET['id'], $_SESSION['idUtilisateur']);
            $infosEtudiant = array(
                'inGroupe' => $inGroupeSAE,
                'inProposition' => $proposition
            );
            $groupes = $this->model->formationGroupes($_GET['id']);

            $this->view->initSaeDetails($groupes, $infosEtudiant, $etudiants, $profs, $saes, $champs, $repId, $ressource, $rendus, $soutenances, $rendusDeposer, $supportsDeposer, $allRessource);
        } else {
            header('Location: index.php');
        }
    }

    private function initPageSoutenanceCalendrier()
    {
        $idSoutenance = isset($_POST['idSoutenance']) ? $_POST['idSoutenance'] : exit("idSoutenance not set");
        $soutenance =  $this->model->getSoutenanceById($idSoutenance)[0];
        $titre = $soutenance['titre'];
        $temps = $soutenance['dureeMinutes'];
        $listeGroupe = $this->model->getGroupeDeLaSae($_GET['id']);
        $listeGroupeAvecPassage = $this->model->getGroupeAvecPassageSoutenance($idSoutenance);

        $firstcpt = 0;
        foreach($listeGroupe as $groupe){
            $find = false;
            foreach($listeGroupeAvecPassage as $groupeAvecPassage){
                if($groupe['idgroupe'] == $groupeAvecPassage['idGroupe']){
                    $listeGroupe[$firstcpt]['passage'] = $groupeAvecPassage['date'];
                    $find = true;
                }
            }
            if(!$find) {
                $listeGroupe[$firstcpt]['passage'] = null;
            }
            $firstcpt++;
        }

        $this->view->initPageSoutenance($titre, $idSoutenance, $_GET['id'], $listeGroupe, $temps);
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

    private function initSoutenance()
    {
        $soutenances = $this->model->listeSoutenanceOuEstJuryParSae($_GET['id'], $_SESSION['idUtilisateur']);
        $sae = $this->model->getSaeById($_GET['id']);

        if($this->model->estProfSae($_GET['id'], $_SESSION['idUtilisateur']))
            $this->view->initPageListeSoutenance($sae, $soutenances, $_GET['id']);
        else
            header("Location: index.php?module=sae&action=details&id=" . $_GET['id']);
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

    private function inputChamp()
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
        $heureLimite = $_POST['heureLimiteRendu'];

        $dateLimiteComplete = $dateLimite . ' ' . $heureLimite;

        $estNote = isset($_POST['renduNote']) ? true : false;
        $coeff = isset($_POST['coeff']) ? $_POST['coeff'] : null;

        $etudiants = $this->model->etudiantQuiOnGroupeDansSAE($idSae);
        $this->model->createRendu($titre, $dateLimiteComplete, $idSae, $estNote, $coeff, $etudiants);


        header("Location: index.php?module=sae&action=details&id=" . $idSae);
    }

    private function delRendu()
    {
        $idRendu = $_GET['id'];

        $this->model->delRendu($idRendu);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delRessourceSAE()
    {
        $idRessource = $_GET['idRessource'];
        $idSAE = $_GET['id'];

        $this->model->delRessourceSAE($idSAE, $idRessource);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    private function updateRendu()
    {
        $titre = $_POST['titreRendu'];
        $dateLimite = $_POST['dateLimiteRendu'];
        $heureLimite = $_POST['heureLimiteRendu'];

        $dateLimiteComplete = $dateLimite . ' ' . $heureLimite;

        $idRendu = $_POST['idRenduAModifier'];

        $this->model->updateRendu($idRendu, $titre, $dateLimiteComplete);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    private function createSoutenance()
    {
        $idSAE = $_GET['id'];
        $titre = $_POST['titreSoutenance'];
        $date = $_POST['dateSoutenance'];
        $duree = $_POST['dureeSoutenance'];
        $salle = $_POST['salleSoutenance'];

        $etudiants = $this->model->etudiantQuiOnGroupeDansSAE($idSAE);
        $this->model->createSoutenance($titre, $date, $salle, $duree, $idSAE, $etudiants);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delSoutenance()
    {
        $idSoutenance = $_GET['id'];

        $this->model->delSoutenance($idSoutenance);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function updateSoutenance()
    {
        $idSoutenance = $_POST['idSoutenanceAModifier'];
        $titre = $_POST['titreSoutenance'];
        $date = $_POST['dateSoutenance'];
        $duree = $_POST['dureeSoutenance'];
        $salle = $_POST['salleSoutenance'];

        $this->model->updateSoutenance($idSoutenance, $duree, $titre, $salle, $date);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function createChamp()
    {
        $idSae = $_GET['id'];
        $nom = $_POST['nomChamp'];

        $this->model->createChamp($idSae, $nom);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function updateSujet()
    {
        $idSae = $_GET['id'];
        $sujet = $_POST['sujet'];

        $this->model->updateSujet($idSae, $sujet);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delSujet()
    {
        $idSae = $_GET['id'];

        $this->model->delSujet($idSae);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function createRessource()
    {
        $nom = $_POST['nomRessource'];
        $contenue = $_POST['contenuRessource'];

        $this->model->createRessource($nom, $contenue);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function addRessource()
    {
        $idSae = $_GET['id'];
        $idRessource = $_POST['ressourceSelect'];

        $this->model->addRessourceSAE($idSae, $idRessource);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function delChamps()
    {
        $idChamp = $_GET['id'];

        $this->model->delChamp($idChamp);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function ajoutProf()
    {
        $idProf = $_POST['idPers'];
        $idSAE = $_GET['id'];
        if ($_POST['poste'] == 'inter'){
            $this->model->ajoutIntervenant($idSAE, $idProf);
        }
        else {
            $this->model->ajoutCResponsables($idSAE, $idProf);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function ajout()
    {
        $idChamp = $_GET["idchamp"];
        if (isset($_POST["reponse" . $idChamp])) {
            $reponse = $_POST["reponse" . $idChamp];

            $this->model->ajoutChamp($idChamp, $_SESSION['idUtilisateur'], $reponse);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function depotSupport()
    {
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $idSoutenance = isset($_POST['idSaeDepotSupport']) ? $_POST['idSaeDepotSupport'] : exit("idSupport not set");
        $file = isset($_FILES['fileInputSupport']) ? $_FILES['fileInputSupport'] : exit("file not set");
        $fileName = $_FILES['fileInputSupport']['name'];
        
        $depotreussi = $this->model->uploadFileSupport($file, $idSoutenance, $fileName, $idSae);
        header("Location: index.php?module=sae&action=details&id=".$idSae);
    }

    private function suprimmerDepotRenduGroupe(){
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $idGroupe = $this->model->getMyGroupId($idSae)[0][0];
        $idDepot = isset($_POST['idDepotSupressionRendu']) ? $_POST['idDepotSupressionRendu'] : exit("idDepot not set");

        $this->model->suprimmerDepotGroupeRendu($idDepot, $idGroupe);
        header("Location: index.php?module=sae&action=details&id=".$idSae);
    }

    private function suprimmerDepotSupportGroupe(){
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $idGroupe = $this->model->getMyGroupId($idSae)[0][0];
        $idDepot = isset($_POST['idDepotSupressionSupport']) ? $_POST['idDepotSupressionSupport'] : exit("idSupport not set");

        $this->model->suprimmerDepotGroupeSupport($idDepot, $idGroupe);
        header("Location: index.php?module=sae&action=details&id=".$idSae);
    }

    private function creerPropositionGroupe() {
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $nomGroupe = $_POST['nomGroupe'];
        if (count($_POST['etudiants']) == count(array_unique($_POST['etudiants']))) {

            $_POST['etudiants'] = array_filter($_POST['etudiants']);
            array_push($_POST['etudiants'], $_SESSION['idUtilisateur']);

            $this->model->propositionGroupe($_POST['etudiants'], $idSae, $nomGroupe);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function gererGroupe() {
        $idProposition = $_GET['idproposition'];
        $sae = $this->model->getSAEById($_GET['id'])[0];
        $idEtudiants = [];
        $i = 0;

        while(isset($_POST['etudiant'.$i])) {
            echo "here";
            $idEtudiants[$i] = $_POST['etudiant'.$i];
            $i++;
        }
        if (isset($_POST['Accepter'])){
            $this->model->accepterGroupe($idProposition, $sae, $idEtudiants);
        }
        else {
            $this->model->refuserGroupe($idProposition, $sae, $idEtudiants);
        }
        $idSae = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function placerPassageSoutenance()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST['date'] ?? exit("date not set");
            $duration = $_POST['duration'] ?? exit("duration not set");
            $schedules = $_POST['schedule'] ?? null;
            $idSoutenance = $_GET['idsoutenance'] ?? exit("idSoutenance not set");

            $this->model->creePassageSoutenance($date, $duration, $schedules, $idSoutenance);
        }

        header("Location: index.php?module=sae&action=soutenance&id=".$_GET['id']);
    }

    private function deposerFichierRendu()
    {
        $idRendu = isset($_GET['id']) ? $_GET['id'] : exit("idSae not set");
        $rendus = $this->model->getRendu($idRendu);
        $fichier = $rendus[0]['fichier'];


        $apiUrl = "http://saemanager-api.atwebpages.com/api/api.php?file=" . urlencode($fichier);
        header("Location: $apiUrl");
    }

    private function deposerFichierRessource()
    {
        $idSae = $_GET['id'];
        $nom = $_POST['nomRessource'];
        $file = isset($_FILES['fileInputRessource']) ? $_FILES['fileInputRessource'] : exit("file not set");
        $fileName = $_FILES['fileInputRessource']['name'];

        $depotreussi = $this->model->uploadFileRessource($file, $fileName, $nom);
        header("Location: index.php?module=sae&action=details&id=" . $idSae);
    }


    private function initRessource()
    {

        $ressource = $this->model->getRessource();
        $myRessources = $this->model->getRessourceInscrit();
        $this->view->initRessources($ressource, $myRessources);
    }

    private function delRessource()
    {

        $idRessource = $_GET['idRessource'];

        $this->model->delRessource($idRessource);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
