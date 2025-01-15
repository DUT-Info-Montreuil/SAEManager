<?php

class SAEModel extends Connexion
{

    // GET

    public function getSAEsByPersonneId($idPersonne)
    {
        $req = "SELECT SAE.nomSae, SAE.idSAE
                FROM Personne
                INNER JOIN EtudiantGroupe ON Personne.idPersonne = EtudiantGroupe.idEtudiant
                INNER JOIN Groupe ON Groupe.idGroupe = EtudiantGroupe.idGroupe
                INNER JOIN SAE ON SAE.idSAE = Groupe.idSAE
                WHERE Personne.idPersonne = :idPersonne
                ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getSAEById($idSAE)
    {
        $req = "SELECT *
                FROM SAE
                WHERE idSAE = :idSAE
                ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function uploadFileRendu($file, $idSae, $fileName, $idRendu){
        $newFileName = $this->uploadFichier($fileName, $file, "none");
        if($newFileName){
            $idGroupe = $this->getMyGroupId($idSae);
            $req = "INSERT INTO RenduGroupe VALUES (:idRendu, :idGroupe, :fichier, :dateDepot)";

            $currentDateTime = date('Y-m-d H:i:s');

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idRendu", $idRendu);
            $pdo_req->bindValue(":idGroupe", $idGroupe[0][0]);
            $pdo_req->bindValue(":fichier", $newFileName);
            $pdo_req->bindValue(":dateDepot", $currentDateTime);
            $pdo_req->execute();
            return true;
        }
        return false;
    }

    function uploadFileSupport($file, $idSoutenance, $fileName, $idSae){
        $newFileName = $this->uploadFichier($fileName, $file, "none");
        if($newFileName){
            $idGroupe = $this->getMyGroupId($idSae);
            $req = "INSERT INTO SupportSoutenance VALUES (:idSoutenance, :idGroupe, :fichier)";

            $currentDateTime = date('Y-m-d H:i:s');
            var_dump($idGroupe);
            var_dump($idSoutenance);
            var_dump($fileName);
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
            $pdo_req->bindValue(":idGroupe", $idGroupe[0][0]);
            $pdo_req->bindValue(":fichier", $fileName);
            $pdo_req->execute();
            return true;
        }
        return false;
    }

    public function uploadFichier($fileName, $fileInput, $color) {
        $dossier = './files/';

        // Vérifiez ou créez le dossier
        if (!file_exists($dossier)) {
            if (!mkdir($dossier, 0777, false)) {
                echo "Impossible de créer le dossier : $dossier";
                return false;
            }
        }

        if(file_exists($dossier . $fileName))
            $fileName = "_".$fileName;
    
        // Déplacez le fichier téléchargé
        if (move_uploaded_file($fileInput['tmp_name'], $dossier . $fileName)) {
            echo "Fichier '$fileName' téléchargé avec succès dans le dossier '$dossier' !<br>";
            echo "Nom entré par l'utilisateur : '$fileName'<br>";
            echo "Couleur choisie : '$color'<br>";
            return $fileName;
        } else {
            echo "Erreur lors du déplacement du fichier.";
            return false;
        }
    }

    public function deleteFichier($fileName){
        $dossier = './files/';

            if (!file_exists($dossier)) 
                if (!mkdir($dossier, 0777, false)) {
                    echo "Impossible de créer le dossier : $dossier";
                    return false;
                }
            if(file_exists($dossier.$fileName))
                return unlink($dossier.$fileName);
        return false;
    }

    public function getChampBySAE($idSAE)
    {
        $req = "SELECT nomchamp, idChamps
                FROM Champs
                INNER JOIN SAE ON Champs.idSAE = SAE.idSAE
                WHERE SAE.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getReponseIdBySAE($idChamp)
    {
        $req = "SELECT idChamp
                FROM reponsesChamp";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return array_column($pdo_req->fetchAll(PDO::FETCH_ASSOC), 'idChamp');
    }

    public function getRessourceBySAE($idSAE)
    {
        $req = "SELECT contenu
                FROM Ressource
                INNER JOIN RessourcesSAE ON RessourcesSAE.idRessource = Ressource.idRessource
                INNER JOIN SAE ON RessourcesSAE.idSAE = SAE.idSAE
                WHERE SAE.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }


    public function getRenduBySAE($idSAE)
    {

        $req = "
                SELECT Rendu.nom, Rendu.dateLimite, Rendu.idRendu
                FROM Rendu
                INNER JOIN SAE ON SAE.idSAE = Rendu.idSAE
                WHERE SAE.idSAE = :idSAE
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getSoutenanceBySAE($idSAE)
    {
        $req = "SELECT Soutenance.idSoutenance, Soutenance.titre, Soutenance.date, Soutenance.salle
                FROM Soutenance
                INNER JOIN SAE ON SAE.idSAE = Soutenance.idSAE
                WHERE SAE.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getMyGroupId($idSAE)
    {

        // Changer l'idPersonne

        $req = "SELECT g.idGroupe
                FROM Personne
                INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = Personne.idPersonne
                INNER JOIN Groupe g ON g.idGroupe = EtudiantGroupe.idGroupe
                WHERE g.idSAE = :idSAE AND Personne.idPersonne = :idPersonne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idPersonne", $_SESSION['idUtilisateur']);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getMyGroup($idSAE, $GroupeID)
    {
        foreach ($GroupeID as $id) {
            $idGroupe = $id['idGroupe'];
        }

        $req = "SELECT idPersonne, p.nom, prenom, photoDeProfil, g.idGroupe, idSAE
                FROM Personne p
                INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = p.idPersonne
                INNER JOIN Groupe g ON EtudiantGroupe.idGroupe = g.idGroupe
                WHERE g.idSAE = :idSAE AND g.idGroupe = :idGroupe";


        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idGroupe", $idGroupe);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getSAEResponsable($idSAE)
    {

        $req = "SELECT p.idPersonne, p.nom, p.prenom
                FROM Personne p
                INNER JOIN SAE ON p.idPersonne = SAE.idResponsable
                WHERE SAE.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getNote($idSAE,  $groupeID)
    {

        foreach ($groupeID as $id) {
            $idGroupe = $id['idGroupe'];
        }
        $req = "SELECT r.nom, note, coeff
                FROM Note
                INNER JOIN Evaluation ON Evaluation.idEvaluation = Note.idEval
                INNER JOIN EtudiantGroupe ON Note.idEleve = EtudiantGroupe.idEtudiant
                INNER JOIN Rendu r ON r.idEvaluation = Evaluation.idEvaluation
                WHERE EtudiantGroupe.idGroupe = :groupeID AND r.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":groupeID", $idGroupe);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getNoteSoutenance($idSAE, $groupeID)
    {

        foreach ($groupeID as $id) {
            $idGroupe = $id['idGroupe'];
        }

        $req = "SELECT s.titre, note, coeff
                FROM Note
                INNER JOIN Evaluation ON Evaluation.idEvaluation = Note.idEval
                INNER JOIN EtudiantGroupe ON Note.idEleve = EtudiantGroupe.idEtudiant
                INNER JOIN Soutenance s ON s.idEvaluation = Evaluation.idEvaluation
                WHERE EtudiantGroupe.idGroupe = :groupeID AND s.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":groupeID", $idGroupe);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    // POST

    public function ajoutChamp ($idChamp, $idEleve, $reponse) {
		$req = "INSERT INTO reponsesChamp (idChamp, idEleve, reponse) VALUES (:idChamp, :idEleve, :reponse)";
		$pdo_req = self::$bdd->prepare($req);
		$pdo_req->bindValue("idChamp", $idChamp);
		$pdo_req->bindValue("idEleve", $idEleve);
        $pdo_req->bindValue("reponse", $reponse);
		$pdo_req->execute();
		if ($pdo_req->rowCount() == 0)
			return false;
		else 
			return true;
	}

    public function getRenduEleve($idRendu, $idSae){
        $idGroupe = $this->getMyGroupId($idSae)[0]['idGroupe'];
        $req = "
                SELECT RenduGroupe.idRendu, RenduGroupe.dateDepot, RenduGroupe.fichier
                FROM RenduGroupe
                WHERE idRendu = :idRendu AND idGroupe = :idGroupe
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idRendu", $idRendu);
        $pdo_req->bindValue(":idGroupe", $idGroupe);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getSupportEleve($idSoutenance, $idSae){
        $idGroupe = $this->getMyGroupId($idSae)[0]['idGroupe'];
        $req = "
                SELECT SupportSoutenance.idSoutenance, SupportSoutenance.idGroupe, SupportSoutenance.support
                FROM SupportSoutenance
                WHERE idSoutenance = :idSoutenance AND idGroupe = :idGroupe
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->bindValue(":idGroupe", $idGroupe);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function suprimmerDepotGroupeRendu($idDepot, $idGroupe){
        $idSae = $this->getSAEById($_SESSION['idUtilisateur'])[0]['idSAE'];
        $rendu = $this->getRenduEleve($idDepot, $idSae);
        $fileName = $rendu[0]['fichier'];
        $req = "
                DELETE FROM RenduGroupe
                WHERE RenduGroupe.idRendu = :idRendu AND RenduGroupe.idGroupe = :idGroupe        
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idRendu", $idDepot);
        $pdo_req->bindValue(":idGroupe", $idGroupe);
        if($pdo_req->execute()){
            return $this->deleteFichier($fileName);
        }
        return false;
    }
    
    public function suprimmerDepotGroupeSupport($idDepot, $idGroupe){
        $idSae = $this->getSAEById($_SESSION['idUtilisateur'])[0]['idSAE'];
        $support = $this->getSupportEleve($idDepot, $idSae);
        $fileName = $support[0]['support'];
        $req = "
                DELETE FROM SupportSoutenance
                WHERE SupportSoutenance.idSoutenance = :idSoutenance AND SupportSoutenance.idGroupe = :idGroupe        
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idDepot);
        $pdo_req->bindValue(":idGroupe", $idGroupe);
        if($pdo_req->execute()){
            return $this->deleteFichier($fileName);
        }
        return false;
    }

    public function didGroupDropRendu($idRendu, $idSae){
        return count($this->getRenduEleve($idRendu, $idSae))!=0;
    }

    public function didGroupeDropSupport($idSoutenance, $idSae){
        return count($this->getSupportEleve($idSoutenance, $idSae))!=0;
    }
}
