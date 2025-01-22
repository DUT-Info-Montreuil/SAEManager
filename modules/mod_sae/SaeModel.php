<?php
class SaeModel extends Connexion
{

    public function getSAEsByPersonneId($idPersonne)
    {
        if ($_SESSION['estProfUtilisateur']) {
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

    function uploadFileRendu($file, $idSae, $fileName, $idRendu)
    {
        $newFileName = $this->uploadFichier($file, "none");
        if ($newFileName) {
            $idGroupe = $this->getMyGroupId($idSae);
            $req = "INSERT INTO RenduGroupe VALUES (:idRendu, :idGroupe, :fichier, :dateDepot)";

            $currentDateTime = date('Y-m-d H:i:s');

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idRendu", $idRendu);
            $pdo_req->bindValue(":idGroupe", $idGroupe[0][0]);
            $pdo_req->bindValue(":fichier", $newFileName['file']);
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

    function uploadFileSupport($file, $idSoutenance, $fileName, $idSae)
    {
        $newFileName = $this->uploadFichier($file, "none");
        if ($newFileName) {
            $idGroupe = $this->getMyGroupId($idSae);
            $req = "INSERT INTO SupportSoutenance VALUES (:idSoutenance, :idGroupe, :fichier)";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
            $pdo_req->bindValue(":idGroupe", $idGroupe[0][0]);
            $pdo_req->bindValue(":fichier", $newFileName['file']);
            $pdo_req->execute();
            return true;
        }
        return false;
    }

    function uploadFileRessource($file, $fileName, $nom)
    {
        $newFileName = $this->uploadFichier($file, "none");

        if ($newFileName) {
            $req = "INSERT INTO Ressource (contenu, couleur, nom) VALUES (:contenu, :couleur, :nom)";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":contenu", $newFileName['file']);
            $pdo_req->bindValue(":couleur", "none");
            $pdo_req->bindValue(":nom", $nom);
            $pdo_req->execute();

            return true;
        }

        return false;
    }

    public function uploadFichier($fileInput, $color)
    {
        $apiUrl = 'http://saemanager-api.atwebpages.com/api/api.php';

        // Vérifiez si un fichier a été envoyé
        if (!isset($fileInput['tmp_name']) || !is_uploaded_file($fileInput['tmp_name'])) {
            echo "Aucun fichier valide n'a été téléchargé.";
            return false;
        }

        // Configurez cURL pour envoyer le fichier directement
        $curl = curl_init();

        $file = new CURLFile($fileInput['tmp_name'], $fileInput['type'], $fileInput['name']);
        $postData = [
            'file' => $file,
            'color' => $color,
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo "Erreur cURL : " . curl_error($curl);
            curl_close($curl);
            return false;
        }

        curl_close($curl);

        echo "Réponse de l'API : $response<br>";
        return json_decode($response, true);
    }


    public function deleteFichier($fileName)
    {
        $apiUrl = 'http://saemanager-api.atwebpages.com/api/api.php';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl . '?file=' . urlencode($fileName));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo "Erreur cURL : " . curl_error($curl);
            curl_close($curl);
            return false;
        }

        curl_close($curl);

        $responseData = json_decode($response, true);
        if (isset($responseData['message']) && $responseData['message'] == 'Fichier supprimé avec succès') {
            echo "Fichier '$fileName' supprimé avec succès via l'API.";
            return true;
        } else {
            echo "Erreur lors de la suppression du fichier via l'API : " . (isset($responseData['error']) ? $responseData['error'] : 'Erreur inconnue');
            return false;
        }
    }

    public function suprimmerDepotGroupeRendu($idDepot, $idGroupe, $idSae)
    {

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

    public function getMySAE()
    {
        $req = "SELECT SAE.idSAE, SAE.nomSae
                FROM SAE
                INNER JOIN EleveInscritSae ON SAE.idSAE = EleveInscritSae.idSAE
                WHERE EleveInscritSae.idEleve = :idEleve";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idEleve", $_SESSION['idUtilisateur'], PDO::PARAM_INT);
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
        else{
            $this->creeNotification($idResp, "Vous avez été assigné coresponsable à une SAE.", $idSAE, "index.php?module=sae&action=details&id=$idSAE");
            return true;
        }
    }
    public function suprimmerDepotGroupeSupport($idDepot, $idGroupe)
    {
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
        $idGroupe = $this->getMyGroupId($idSae);
        if($idGroupe){
            $idGroupe = $idGroupe[0]["idGroupe"];
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
    }

    public function didGroupDropRendu($idRendu, $idSae)
    {
        $count = $this->getRenduEleve($idRendu, $idSae);
        if($count)
            return count($count) != 0;
        return false;
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

    function getRendu($idRendu)
    {
        $req = "SELECT * FROM RenduGroupe WHERE idRendu = :idRendu";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idRendu", $idRendu, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getSupport($idSoutenance)
    {
        $req = "SELECT * FROM SupportSoutenance WHERE idSoutenance = :idSoutenance";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSoutenance", $idSoutenance, PDO::PARAM_INT);
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

        if ($GroupeID){
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
        return null;
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

    function getNote($idSAE, $groupeID)
    {
        $idEleve = $_SESSION['idUtilisateur'];
        // $req = "SELECT r.nom, note, coef
        //         FROM Note
        //         INNER JOIN Evaluation ON Evaluation.idEval = Note.idEval
        //         INNER JOIN EtudiantGroupe ON Note.idEleve = EtudiantGroupe.idEtudiant
        //         INNER JOIN Rendu r ON r.idEvaluation = Evaluation.idEval
        //         WHERE EtudiantGroupe.idGroupe = :groupeID AND r.idSAE = :idSAE";

        $req = "SELECT r.nom, Notes.note, Evaluation.coef,Notes.idRendu
                FROM Notes
                INNER JOIN Evaluation ON Evaluation.idEval = Notes.idEval
                INNER JOIN Rendu r ON r.idEvaluation = Evaluation.idEval
                WHERE Notes.idEleve = :idEleve AND r.idSAE = :idSAE";

        // $req = "SELECT n.idRendu, r.nom, note, Evaluation.coef, AVG(note) as moyenne
        //         FROM Notes n
        //         INNER JOIN Evaluation ON Evaluation.idEval = n.idEval
        //         INNER JOIN Rendu r ON r.idEvaluation = Evaluation.idEval
        //         WHERE n.ideleve = :idEleve AND r.idSAE = :idSAE
        //         GROUP BY n.idRendu, r.nom
        //         ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEleve", $idEleve);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getNoteSoutenance($idSAE)
    {
        $idEleve = $_SESSION['idUtilisateur'];

        // $req = "SELECT s.titre, note, coef
        //         FROM Note
        //         INNER JOIN Evaluation ON Evaluation.idEval = Note.idEval
        //         INNER JOIN EtudiantGroupe ON Note.idEleve = EtudiantGroupe.idEtudiant
        //         INNER JOIN Soutenance s ON s.idEvaluation = Evaluation.idEval
        //         WHERE EtudiantGroupe.idGroupe = :groupeID AND s.idSAE = :idSAE";

        $req = "SELECT s.titre, NotesSoutenance.note, Evaluation.coef, NotesSoutenance.idSoutenance
                FROM NotesSoutenance
                INNER JOIN Evaluation ON Evaluation.idEval = NotesSoutenance.idEval
                INNER JOIN Soutenance s ON s.idEvaluation = Evaluation.idEval
                WHERE NotesSoutenance.idEleve = :idEleve AND s.idSAE = :idSAE";

// $req = "SELECT r.nom, Notes.note, Evaluation.coef,Notes.idRendu
// FROM Notes
// INNER JOIN Evaluation ON Evaluation.idEval = Notes.idEval
// INNER JOIN Rendu r ON r.idEvaluation = Evaluation.idEval
// WHERE Notes.idEleve = :idEleve AND r.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idEleve", $idEleve);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
    public function getReponseIdBySAE($idChamp)
    {
        $req = "SELECT idChamp
                FROM reponsesChamp
                WHERE idEleve = :idEleve";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idEleve", $_SESSION['idUtilisateur']);
        $pdo_req->execute();
        return array_column($pdo_req->fetchAll(PDO::FETCH_ASSOC), 'idChamp');
    }
    public function didGroupeDropSupport($idSoutenance, $idSae)
    {
        return $this->getSupportEleve($idSoutenance, $idSae);
    }
    public function getSupportEleve($idSoutenance, $idSae)
    {
        $idGroupe = $this->getMyGroupId($idSae);

        if ($idGroupe) {
            $id = $idGroupe[0]['idGroupe'];
            $req = "
                SELECT SupportSoutenance.idSoutenance, SupportSoutenance.idGroupe, SupportSoutenance.support
                FROM SupportSoutenance
                WHERE idSoutenance = :idSoutenance AND idGroupe = :idGroupe
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSoutenance", $idSoutenance);
        $pdo_req->bindValue(":idGroupe", $id);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
        }
        return array();
    }
    public function getProfsBySAE($idSAE){
        $req = "SELECT idPersonne, prenom, nom
                FROM Personne
                WHERE estProf = 1
                AND idPersonne not in (
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
        $req = "SELECT Personne.idPersonne , Personne.prenom, Personne.nom
                FROM Personne
                WHERE Personne.estProf = 1
                AND Personne.idPersonne  = :idPersonne
                AND Personne.idPersonne in (
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
        $pdo_req->bindValue(":idSAE", $idSae);
        $pdo_req->bindValue(":idPersonne", $idPersonne);
        $pdo_req->execute();

        $profsSae = $pdo_req->fetchAll();
        if(count($profsSae)==0)
            return false;
        return $profsSae[0]['idPersonne'] == $idPersonne;
    }

    public function ajoutIntervenant($idSAE, $idIntervenant)
    {
        $req = "INSERT INTO IntervenantSAE (idSAE, idIntervenant) VALUES (:idSAE, :idIntervenant)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->bindValue(":idIntervenant", $idIntervenant);
        $pdo_req->execute();
        if ($pdo_req->rowCount() == 0)
            return false;
        else{
            $this->creeNotification($idIntervenant, "Vous avez été assigné intervenant à une SAE.", $idSAE, "index.php?module=sae&action=details&id=$idSAE");
            return true;
        }
    }
    public function getRessourceInscrit()
    {

        if ($_SESSION['estProfUtilisateur']) {
            $req_prof = "
                SELECT DISTINCT SAE.idSAE, SAE.nomSae, Ressource.contenu, Ressource.nom, RessourcesSAE.idRessource
                FROM SAE
                INNER JOIN RessourcesSAE ON SAE.idSAE = RessourcesSAE.idSAE
                INNER JOIN Ressource ON Ressource.idRessource = RessourcesSAE.idRessource
                WHERE SAE.idResponsable = :idResponsable
            ";

            $pdo_req_prof = self::$bdd->prepare($req_prof);
            $pdo_req_prof->bindValue(":idResponsable", $_SESSION['idUtilisateur'], PDO::PARAM_INT);
            $pdo_req_prof->execute();

            $ressources_prof = $pdo_req_prof->fetchAll();
            return $ressources_prof;
        }

        $req_eleve = "
            SELECT *
            FROM EleveInscritSae
            INNER JOIN RessourcesSAE ON EleveInscritSae.idSAE = RessourcesSAE.idSAE
            WHERE idEleve = :idEleve
        ";

        $pdo_req_eleve = self::$bdd->prepare($req_eleve);
        $pdo_req_eleve->bindValue(":idEleve", $_SESSION['idUtilisateur'], PDO::PARAM_INT);
        $pdo_req_eleve->execute();

        $ressources_eleve = $pdo_req_eleve->fetchAll();

        return $ressources_eleve;
    }


    // POST

    function createRendu($titre, $date, $idSAE, $estNote, $coeff, $etudiants)
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


        $nomSae = $this->getSAEById($idSAE)[0]['nomSae'];
        $message = "Un nouveau rendu à été crée dans la sae $nomSae!";
        $redirect = "index.php?module=sae&action=details&id=$idSAE";

        foreach ($etudiants as $etudiant) {
            $this->creeNotification($etudiant['idPersonne'], $message, $idSAE, $redirect);
        }
    }

    function createSoutenance($titre, $date, $salle, $duree, $idSAE, $etudiants)
    {
        $reqEval = "INSERT INTO Evaluation (nom, coef, IntervenantEvaluateur) VALUES (:nom, :coeff, NULL)";
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

        $nomSae = $this->getSAEById($idSAE)[0]['nomSae'];
        $message = "Une nouvelle soutenance à été ajoutée à la sae $nomSae!";
        $redirect = "index.php?module=sae&action=details&id=$idSAE";

        foreach ($etudiants as $etudiant) {
            $this->creeNotification($etudiant['idPersonne'], $message, $idSAE, $redirect);
        }
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

    public function getEtudiantsPasInscrits($idSAE) {
        $req = "SELECT idPersonne, prenom, nom
                FROM Personne
                WHERE estProf = 0
                AND idPersonne not in (SELECT idEleve
                                    FROM EleveInscritSae
                                    INNER JOIN Personne ON idPersonne = idEleve
                                    WHERE idSAE = :idSAE)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
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

    public function etudiantQuiOnGroupeDansSAE($idSAE)
    {
        $req = "SELECT *
                FROM Personne
                INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = Personne.idPersonne
                INNER JOIN Groupe ON EtudiantGroupe.idGroupe = Groupe.idgroupe
                INNER JOIN SAE ON Groupe.idSAE = SAE.idSAE
                WHERE SAE.idSAE = :idSAE";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idSAE", $idSAE);
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

    public function accepterGroupe($idProposition, $sae, $idEtudiants) {
        $nomSae = $sae['nomSae'];
        $idSAE = $sae['idSAE'];
        $message = "Votre proposition de groupe pour la sae $nomSae a été validé !";
        $redirect = "index.php?module=sae&action=details&id=$idSAE";

        foreach ($idEtudiants as $etudiant) {
            $this->creeNotification($etudiant, $message, $idSAE, $redirect);
        }



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

    public function refuserGroupe($idProposition, $sae, $idEtudiants) {
        if($this->eraseProposition($idProposition)){
            $nomSae = $sae['nomSae'];
            $idSAE = $sae['idSAE'];
            $message = "Votre proposition de groupe pour la sae $nomSae a été refusé.";
            $redirect = "index.php?module=sae&action=details&id=$idSAE";

            foreach ($idEtudiants as $etudiant) {
                $this->creeNotification($etudiant, $message, $idSAE, $redirect);
            }
        }
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

        return true;
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
                    WHERE DATE(PassageSoutenance.date) = :date
                    AND idSoutenance = :idSoutenance";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":date", $date);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
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
                    WHERE idSoutenance = :idSoutenance";

            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
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

    function delRessource($idRessource)
    {
        $reqSAE = "DELETE FROM RessourcesSAE WHERE idRessource = :id";
        $pdo_reqSAE = self::$bdd->prepare($reqSAE);
        $pdo_reqSAE->bindValue(":id", $idRessource);
        $pdo_reqSAE->execute();

        $reqRessource = "DELETE FROM Ressource WHERE idRessource = :id";
        $pdo_reqRessource = self::$bdd->prepare($reqRessource);
        $pdo_reqRessource->bindValue(":id", $idRessource);
        $pdo_reqRessource->execute();
    }

    private function creeNotification($idUtilisateur, $message, $idSAE, $redirect)
    {
        $req = "INSERT INTO Notifications VALUES (DEFAULT, :idPersonne, :message, :idSaeProvenance, :lienForm, :date)";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idPersonne", $idUtilisateur);
        $pdo_req->bindValue(":message", $message);
        $pdo_req->bindValue(":idSaeProvenance", $idSAE);
        $pdo_req->bindValue(":lienForm", $redirect);
        $pdo_req->bindValue(":date", date("Y-m-d H:i:s"));
        $pdo_req->execute();
        return true;
    }

    public function inscrireEtudiantSAE($idSAE, $idEtudiant) {
        $req = "INSERT INTO EleveInscritSae (idSAE, idEleve) VALUES (:idSAE, :idEleve)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            ':idSAE' => $idSAE,
            ':idEleve' => $idEtudiant
        ]);
    }

    public function inscrireEtudiantsSAE($idSAE, $idEtudiants) {
        foreach($idEtudiants as $id) {
            $this->inscrireEtudiantSAE($idSAE, $id);
        }
    }
}
