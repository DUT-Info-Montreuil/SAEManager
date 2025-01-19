<?php
class SaeModel extends Connexion
{

    public function getSAEsByPersonneId($idPersonne){
        if($_SESSION['estProfUtilisateur']){
            $req = "SELECT SAE.nomSae, SAE.idSAE
            FROM SAE
            WHERE SAE.idResponsable = :idPersonne

            UNION

            SELECT DISTINCT SAE.nomSae, SAE.idSAE
            FROM SAE
            INNER JOIN ResponsablesSAE ON SAE.idSAE = ResponsablesSAE.idSAE
            INNER JOIN Personne ON Personne.idPersonne = ResponsablesSAE.idResp
            WHERE Personne.idPersonne = :idPersonne

            UNION

            SELECT DISTINCT SAE.nomSae, SAE.idSAE
            FROM SAE
            INNER JOIN IntervenantSAE ON SAE.idSAE = IntervenantSAE.idSAE
            INNER JOIN Personne ON Personne.idPersonne = IntervenantSAE.idIntervenant
            WHERE Personne.idPersonne = :idPersonne";
        }else{
            $req = "SELECT SAE.nomSae, SAE.idSAE
                        FROM Personne
                        INNER JOIN EleveInscritSae ON Personne.idPersonne = EleveInscritSae.idEleve
                        INNER JOIN SAE ON SAE.idSAE = EleveInscritSae.idSAE
                        WHERE Personne.idPersonne = :idPersonne
                        ";
        }
        
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

    function getSoutenanceById($idSoutenance){
        $req = "SELECT *
                FROM Soutenance
                WHERE Soutenance.idSoutenance = :idSoutenance";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSoutenance", $idSoutenance);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
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


    public function getRessourceBySAE($idSAE)
    {
        $req = "SELECT Ressource.idRessource, contenu, nom
                FROM Ressource
                INNER JOIN RessourcesSAE ON RessourcesSAE.idRessource = Ressource.idRessource
                INNER JOIN SAE ON RessourcesSAE.idSAE = SAE.idSAE
                WHERE SAE.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getRessource()
    {
        $req = "SELECT * FROM Ressource";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
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
    public function ajoutChamp($idChamp, $idEleve, $reponse)
    {
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

    public function ajoutCResponsables($idSAE, $idResp)
    {
        $req = "INSERT INTO ResponsablesSAE (idSAE, idResp) VALUES (:idSAE, :idResp)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idResp", $idResp);
        $pdo_req->execute();
        if ($pdo_req->rowCount() == 0)
            return false;
        else
            return true;
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

    public function getRenduEleve($idRendu, $idSae)
    {
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

    public function didGroupDropRendu($idRendu, $idSae)
    {
        return count($this->getRenduEleve($idRendu, $idSae)) != 0;
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
                WHERE SAE.idSAE = :idSAE
                UNION
                SELECT p.idPersonne, p.nom, p.prenom
                FROM Personne p
                INNER JOIN IntervenantSAE ON p.idPersonne = IntervenantSAE.idIntervenant
                WHERE IntervenantSAE.idSAE = :idSAE
                UNION
                SELECT p.idPersonne, p.nom, p.prenom
                FROM Personne p
                INNER JOIN ResponsablesSAE ON p.idPersonne = ResponsablesSAE.idResp
                WHERE ResponsablesSAE.idSAE = :idSAE";
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
    public function getReponseIdBySAE($idChamp)
    {
        $req = "SELECT idChamp
                FROM reponsesChamp";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return array_column($pdo_req->fetchAll(PDO::FETCH_ASSOC), 'idChamp');
    }
    public function didGroupeDropSupport($idSoutenance, $idSae){
        return count($this->getSupportEleve($idSoutenance, $idSae))!=0;
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
    public function getProfsBySAE($idSAE){
        $req = "SELECT idPersonne, prenom, nom
                FROM Personne
                WHERE estProf = 1
                AND idPersonne in (
                                        SELECT idResp
                                        FROM ResponsablesSAE
                                        WHERE idSAE = :idSAE
                                        UNION
                                        SELECT idIntervenant
                                        FROM IntervenantSAE
                                        WHERE idSAE = :idSAE
                                        UNION
                                        SELECT idResponsable
                                        FROM SAE
                                        WHERE idSAE = :idSAE)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function estProfSae($idSae, $idPersonne){
        $profsSae = $this->getProfsBySAE($idSae);
        foreach($profsSae as $prof){
            if($prof['idPersonne'] == $idPersonne){
                return true;
            }
        }
        return false;
    }



    // POST

    function createRendu($titre, $date, $idSAE, $estNote, $coeff)
    {
        if ($estNote) {
            $reqEval = "INSERT INTO Evaluation (nom, coeff, responsableEvaluation) VALUES (:nom, :coeff, NULL)";
            $pdo_reqEval = self::$bdd->prepare($reqEval);
            $pdo_reqEval->bindValue(":nom", $titre);
            $pdo_reqEval->bindValue(":coeff", $coeff);
            $pdo_reqEval->execute();

            $idEvaluation = self::$bdd->lastInsertId();

            $reqRendu = "INSERT INTO Rendu (nom, dateLimite, idSAE, idEvaluation) VALUES (:titreRendu, :dateLimite, :idSAE, :idEval)";
            $pdo_reqRendu = self::$bdd->prepare($reqRendu);
            $pdo_reqRendu->bindValue(":titreRendu", $titre);
            $pdo_reqRendu->bindValue(":dateLimite", $date);
            $pdo_reqRendu->bindValue(":idSAE", $idSAE);
            $pdo_reqRendu->bindValue(":idEval", $idEvaluation);
            $pdo_reqRendu->execute();
        } else {
            $reqRendu = "INSERT INTO Rendu (nom, dateLimite, idSAE, idEvaluation) VALUES (:titreRendu, :dateLimite, :idSAE, NULL)";
            $pdo_reqRendu = self::$bdd->prepare($reqRendu);
            $pdo_reqRendu->bindValue(":titreRendu", $titre);
            $pdo_reqRendu->bindValue(":dateLimite", $date);
            $pdo_reqRendu->bindValue(":idSAE", $idSAE);
            $pdo_reqRendu->execute();
        }
    }

    function createSoutenance($titre, $date, $salle, $duree, $idSAE)
    {
        $reqEval = "INSERT INTO Evaluation (nom, coeff, responsableEvaluation) VALUES (:nom, :coeff, NULL)";
        $pdo_req_eval = self::$bdd->prepare($reqEval);
        $pdo_req_eval->bindValue(":nom", $titre);
        $pdo_req_eval->bindValue(":coeff", 1);
        $pdo_req_eval->execute();

        $idEvaluation = self::$bdd->lastInsertId();

        $req = "INSERT INTO Soutenance (dureeMinutes, titre, date, salle, idSAE, idEvaluation) 
            VALUES (:dureeMinutes, :titre, :date, :salle, :idSAE, :idEvaluation)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":dureeMinutes", $duree);
        $pdo_req->bindValue(":titre", $titre);
        $pdo_req->bindValue(":date", $date);
        $pdo_req->bindValue(":salle", $salle);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEvaluation", $idEvaluation);
        $pdo_req->execute();
    }

    function createChamp($idSAE, $nomChamp)
    {
        $req = "INSERT INTO Champs (nomChamp, idSAE) VALUES (:nomChamp, :idSAE)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":nomChamp", $nomChamp);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
    }

    function createRessource($nom, $contenue)
    {
        $req = "INSERT INTO Ressource (nom, contenu, couleur) VALUES (:nom, :contenu, :couleur)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":nom", $nom);
        $pdo_req->bindValue(":contenu", $contenue);
        $pdo_req->bindValue(":couleur", NULL);
        $pdo_req->execute();
    }

    function addRessourceSAE($idSAE, $idRessource)
    {
        $req = "INSERT INTO RessourcesSAE (idSAE, idRessource) VALUES (:idSAE, :idRessource)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idRessource", $idRessource);
        $pdo_req->execute();
    }

    // DEL

    function delRendu($idRendu)
    {
        $req = "DELETE FROM Rendu WHERE idRendu = :idRendu";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idRendu", $idRendu);
        $pdo_req->execute();
    }

    function delRessourceSAE($idSAE, $idRessource)
    {
        $req = "DELETE FROM RessourcesSAE WHERE idSAE = :idSAE AND idRessource = :idRessource";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idRessource", $idRessource);
        $pdo_req->execute();
    }

    function delSoutenance($idSoutenance)
    {
        $req = "DELETE FROM Soutenance WHERE idSoutenance = :idSou";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSou", $idSoutenance);
        $pdo_req->execute();
    }

    function delSujet($idSAE)
    {
        $req = "UPDATE SAE SET sujet = '' WHERE idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
    }

    function delChamp($idChamp)
    {
        $req = "DELETE FROM Champs WHERE idChamps = :idChamp";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idChamp", $idChamp);
        $pdo_req->execute();
    }


    // PUT

    function updateRendu($idRendu, $titre, $date)
    {
        $req = "UPDATE Rendu SET nom = :titreRendu, dateLimite = :dateLimite WHERE idRendu = :idRendu";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":titreRendu", $titre);
        $pdo_req->bindValue(":dateLimite", $date);
        $pdo_req->bindValue(":idRendu", $idRendu);
        $pdo_req->execute();
    }


    function updateSoutenance($idSoutenance, $duree, $titre, $salle, $date)
    {
        $req = "UPDATE Soutenance SET dureeMinutes = :dureeMinutes, titre = :titre, salle = :salle, date = :date WHERE idSoutenance = :idSoutenance";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":dureeMinutes", $duree);
        $pdo_req->bindValue(":titre", $titre);
        $pdo_req->bindValue(":salle", $salle);
        $pdo_req->bindValue(":date", $date);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->execute();
    }

    function updateSujet($idSAE, $sujet)
    {
        $req = "UPDATE SAE SET sujet = :sujet WHERE idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":sujet", $sujet);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
    }

    public function getEtudiantsBySAE($idSAE) {
        $req = "SELECT idEleve, prenom, nom
                FROM EleveInscritSae
                INNER JOIN Personne ON idPersonne = idEleve
                WHERE idSAE = :idSAE
                AND idEleve != :idEleve
                AND idEleve not in (SELECT idEleve
                                    FROM PropositionsEleve
                                    INNER JOIN PropositionsGroupe using(idProposition)
                                    WHERE idSAE = :idSAE)
                AND idEleve not in (SELECT idEtudiant
                                    FROM EtudiantGroupe
                                    INNER JOIN Groupe on EtudiantGroupe.idGroupe = Groupe.idgroupe
                                    WHERE idSAE = :idSAE)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEleve", $_SESSION['idUtilisateur']);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inGroupeBySAE($idSAE) {
        $req = "SELECT count(*)
                FROM Groupe
                INNER JOIN EtudiantGroupe ON Groupe.idgroupe = EtudiantGroupe.idgroupe
                WHERE idSAE = :idSAE
                AND idEtudiant = :idEleve";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEleve", $_SESSION['idUtilisateur']);
        $pdo_req->execute();
        return array_column($pdo_req->fetchAll(PDO::FETCH_ASSOC), 'count(*)')[0] !== 0;
    }

    public function inPropositions($idSAE, $id) {
        $req = "SELECT count(*)
                FROM PropositionsGroupe
                INNER JOIN PropositionsEleve using(idProposition)
                WHERE idSAE = :idSAE
                AND idEleve = :idEleve";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEleve", $id);
        $pdo_req->execute();
        return array_column($pdo_req->fetchAll(PDO::FETCH_ASSOC), 'count(*)')[0] !== 0;
    }

    private function isInscrivablesBySAE($id_etudiants, $idSAE) {
        foreach($id_etudiants as $id) {
            if ($this->inPropositions($idSAE, $id)) {
                return false;
            }
        }
        return true;
    }

    public function propositionGroupe($id_etudiants, $idSAE, $nomGroupe) {
        if ($this->isInscrivablesBySAE($id_etudiants, $idSAE)) {
            $req = "INSERT INTO PropositionsGroupe (idProposition, idSAE, nomGroupe) VALUES (DEFAULT, :idSAE, :nomGroupe)";
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSAE", $idSAE);
            $pdo_req->bindValue(":nomGroupe", $nomGroupe);
            $pdo_req->execute();

            $req = "SELECT max(idProposition)
                    FROM PropositionsGroupe;";
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->execute();
            $idProposition = $pdo_req->fetchAll(PDO::FETCH_ASSOC)[0];
            foreach($id_etudiants as $id) {
                $req = "INSERT INTO PropositionsEleve (idProposition, idEleve) VALUES (:idProposition, :idEleve)";
                $pdo_req = self::$bdd->prepare($req);
                $pdo_req->bindValue(":idProposition", $idProposition['max(idProposition)']);
                $pdo_req->bindValue(":idEleve", $id);
                $pdo_req->execute();
            }
        }
    }

    public function propositionsGroupe($idSAE) {
        $req = "SELECT nomGroupe, nom, prenom, idEleve, idProposition
                FROM PropositionsGroupe
                INNER JOIN PropositionsEleve using(idProposition)
                INNER JOIN Personne on idEleve = idPersonne
                WHERE idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function formationGroupes($idSAE) {
        $eleves = $this->propositionsGroupe($idSAE);
        $groupes = [];
        foreach ($eleves as $eleve) {
            $nomGroupe = $eleve['nomGroupe'];
            $idGroupe = $eleve['idProposition'];
            if (!isset($groupes[$nomGroupe])) {
                $groupes[$nomGroupe] = [
                    'nomGroupe' => $nomGroupe,
                    'idGroupe' => $idGroupe,
                    'etudiants' => []
                ];
            }

            $groupes[$nomGroupe]['etudiants'][] = [
                'nom' => $eleve['nom'],
                'prenom' => $eleve['prenom'],
                'idEleve' => $eleve['idEleve'],
            ];
        }
        return $groupes;
    }

    public function accepterGroupe($idProposition) {

        $req = "INSERT INTO Groupe (idgroupe, nom, imageTitre, idSAE) VALUES (DEFAULT, 
                                        (SELECT nomGroupe FROM PropositionsGroupe WHERE idProposition = :idProposition), DEFAULT, 
                                        (SELECT idSAE FROM PropositionsGroupe WHERE idProposition = :idProposition))";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idProposition", $idProposition);
        $pdo_req->execute();

        $req = "SELECT max(idgroupe)
                FROM Groupe;";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        $idGroupe = $pdo_req->fetchAll(PDO::FETCH_ASSOC)[0]['max(idgroupe)'];

        $req = "SELECT idEleve
                FROM PropositionsEleve
                WHERE idProposition = :idProposition;";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idProposition", $idProposition);
        $pdo_req->execute();
        $id_etudiants = $pdo_req->fetchAll(PDO::FETCH_ASSOC);

        foreach($id_etudiants as $id) {
            $req = "INSERT INTO EtudiantGroupe (idGroupe, idEtudiant) VALUES (:idGroupe, :idEtudiant);";
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idGroupe", $idGroupe);
            $pdo_req->bindValue(":idEtudiant", $id['idEleve']);
            $pdo_req->execute();
        }
        $this->eraseProposition($idProposition);
    }

    public function refuserGroupe($idProposition) {
        $this->eraseProposition($idProposition);
    }

    private function eraseProposition($idProposition) {

        $req = "DELETE FROM PropositionsEleve
                WHERE idProposition = :idProposition";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idProposition", $idProposition);
        $pdo_req->execute();

        $req = "DELETE FROM PropositionsGroupe
                WHERE idProposition = :idProposition";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idProposition", $idProposition);
        $pdo_req->execute();
    }
    public function getGroupeSansPassageDeSoutenance($idSoutenance)
    {
        $req = "SELECT Groupe.idGroupe, Groupe.nom 
        FROM Groupe
        WHERE Groupe.idGroupe NOT IN (
            SELECT distinct(PassageSoutenance.idGroupe)
            FROM PassageSoutenance
            WHERE idSoutenance = :idSoutenance
        )";


        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getGroupeAvecPassageSoutenance($idSoutenance)
    {
        $req = "SELECT *
                FROM PassageSoutenance
                WHERE idSoutenance = :idSoutenance
        ";


        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getGroupeDeLaSae($idSae){
        $req = "SELECT *
        FROM Groupe
        WHERE Groupe.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSae);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function creePassageSoutenance($date, $duration, $schedules, $idSoutenance)
    {
        $req = "SELECT Soutenance.dureeMinutes
        FROM Soutenance
        WHERE idSoutenance = :idSoutenance";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->execute();
        $result = $pdo_req->fetch();

        if($result["dureeMinutes"] == $duration) {
            $req = "DELETE FROM PassageSoutenance
                    WHERE DATE(PassageSoutenance.date) = :date";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":date", $date);
            $pdo_req->execute();

            if($schedules) {
                foreach ($schedules as $schedule) {
                    $idGroupe = explode("|", $schedule)[0];
                    $time = trim(explode("-", explode("|", $schedule)[1])[0]); # récupère l'heure de début du passage
                    $datetime = $date . ' ' . $time . ":00";

                    $req = "INSERT INTO PassageSoutenance VALUES(:idSoutenance, :idGroupe, :time)";
                    $pdo_req = self::$bdd->prepare($req);
                    $pdo_req->bindValue(":idSoutenance", $idSoutenance);
                    $pdo_req->bindValue(":idGroupe", $idGroupe);
                    $pdo_req->bindValue(":time", $datetime);
                    $pdo_req->execute();
                }
            }
        }
        else {
            $req = "UPDATE Soutenance
            SET Soutenance.dureeMinutes = :duration
            WHERE Soutenance.idSoutenance = :idSoutenance";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
            $pdo_req->bindValue(":duration", $duration);
            $pdo_req->execute();

            $req = "DELETE FROM PassageSoutenance
                    WHERE 1=1";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->execute();
        }
    }

    public function listeSoutenanceOuEstJuryParSae($idSae, $idPersonne)
    {
        $req = "SELECT *
                FROM Soutenance
                WHERE Soutenance.idSoutenance IN (SELECT idSoutenance
                                FROM JurySoutenance
                                WHERE idPersonne = :idPersonne)
                AND Soutenance.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idPersonne", $idPersonne);
        $pdo_req->bindValue(":idSAE", $idSae);
        $pdo_req->execute();

        return $pdo_req->fetchAll();
    }
}
