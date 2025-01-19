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
        } else {
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
        $newFileName = $this->uploadFichier($fileName, $file, "none");
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

    function uploadFileSupport($file, $idSoutenance, $fileName, $idSae)
    {
        $newFileName = $this->uploadFichier($fileName, $file, "none");
        if ($newFileName) {
            $idGroupe = $this->getMyGroupId($idSae);
            $req = "INSERT INTO SupportSoutenance VALUES (:idSoutenance, :idGroupe, :fichier)";

            $currentDateTime = date('Y-m-d H:i:s');
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindValue(":idSoutenance", $idSoutenance);
            $pdo_req->bindValue(":idGroupe", $idGroupe[0][0]);
            $pdo_req->bindValue(":fichier", $fileName);
            $pdo_req->execute();
            return true;
        }
        return false;
    }

    function uploadFileRessource($file, $fileName, $nom)
    {
        $newFileName = $this->uploadFichier($fileName, $file, "none");

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

    public function uploadFichier($fileName, $fileInput, $color)
    {
        $apiUrl = 'http://saemanager-api.atwebpages.com/api/api.php';
        $dossier = './files/';

        if (!file_exists($dossier)) {
            if (!mkdir($dossier, 0777, true)) {
                echo "Impossible de créer le dossier : $dossier";
                return false;
            }
        }

        if (file_exists($dossier . $fileName)) {
            $fileName = "_" . $fileName;
        }

        $localFilePath = $dossier . $fileName;
        if (move_uploaded_file($fileInput['tmp_name'], $localFilePath)) {
            echo "Fichier '$fileName' téléchargé avec succès dans le dossier '$dossier' temporairement.<br>";

            $curl = curl_init();
            $file = new CURLFile($localFilePath);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $postData = [
                'file' => $file,
                'color' => $color,
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $postData,
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
        } else {
            echo "Erreur lors du déplacement du fichier local.";
            return false;
        }
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


    public function suprimmerDepotGroupeRendu($idDepot, $idGroupe)
    {
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
        if ($pdo_req->execute()) {
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
        else
            return true;
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
        if ($pdo_req->execute()) {
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

    function getRendu($idRendu)
    {
        $req = "SELECT * FROM RenduGroupe WHERE idRendu = :idRendu";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idRendu", $idRendu, PDO::PARAM_INT);
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

        if ($GroupeID) {
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
    public function didGroupeDropSupport($idSoutenance, $idSae)
    {
        return count($this->getSupportEleve($idSoutenance, $idSae)) != 0;
    }
    public function getSupportEleve($idSoutenance, $idSae)
    {
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
    public function getProfsBySAE($idSAE)
    {
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
}
