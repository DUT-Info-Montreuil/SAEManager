<?php

class SoutenanceModel extends Connexion
{
    
    function getSoutenanceProfByPersonne($idPersonne){
        $req = "SELECT DISTINCT Soutenance.idSoutenance,Soutenance.titre AS Soutenance_nom,SAE.nomSae AS SAE_nom,Soutenance.date,Soutenance.idSAE
                FROM SAE
                JOIN Soutenance ON SAE.idSAE = Soutenance.idSAE
                LEFT JOIN ResponsablesSAE ON SAE.idSAE = ResponsablesSAE.idSAE
                LEFT JOIN IntervenantSAE ON SAE.idSAE = IntervenantSAE.idSAE
                WHERE SAE.idResponsable = :idPersonne OR ResponsablesSAE.idResp = :idPersonne OR IntervenantSAE.idIntervenant = :idPersonne;";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);    
    }

    function getNotesdesSoutenanceProfByPersonne($idPersonne) {
        $req = "
        SELECT DISTINCT 
            sou.idSoutenance,
            sou.titre AS Soutenance_nom,
            sou.date AS Soutenance_date,
            s.nomSae AS SAE_nom,
            s.idSAE AS idSAE,
            e.idEval,
            e.nom AS Eval_nom,
            e.coef AS Eval_coef,
            n.note AS Note_valeur,
            e.IntervenantEvaluateur AS idIntervenantEvaluateur,
            CASE 
                WHEN s.idResponsable = :idPersonne THEN 1
                WHEN rs.idResp = :idPersonne THEN 1
                WHEN e.IntervenantEvaluateur = :idPersonne THEN 1
                ELSE 0 
            END AS PeutEvaluer
        FROM Soutenance sou
        JOIN SAE s ON sou.idSAE = s.idSAE
        LEFT JOIN NotesSoutenance n ON sou.idSoutenance = n.idSoutenance
        LEFT JOIN Evaluation e ON n.idEval = e.idEval
        LEFT JOIN ResponsablesSAE rs ON s.idSAE = rs.idSAE
        LEFT JOIN IntervenantSAE i ON s.idSAE = i.idSAE
        WHERE 
            s.idResponsable = :idPersonne 
            OR rs.idResp = :idPersonne
            OR i.idIntervenant = :idPersonne
            OR e.IntervenantEvaluateur = :idPersonne
        ORDER BY SAE_nom;
        ";
    
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getNotesParGroupeDuneEval($idEval){
        $req = "SELECT DISTINCT 
                    e.idEval,
                    e.nom AS Eval_nom,
                    e.coef AS Eval_coef,
                    n.note AS Note_valeur,
                    n.idEleve,
                    p.nom AS Eleve_nom,
                    p.prenom AS Eleve_prenom
                FROM Evaluation e
                JOIN NotesSoutenance n ON e.idEval = n.idEval
                JOIN Personne p ON n.idEleve = p.idPersonne
                WHERE e.idEval = :idEval
                ORDER BY n.idEleve;";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idEval", $idEval, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    function getElevesParGroupe($idSAE) {
        $req = "
        SELECT DISTINCT
            p.idPersonne,
            p.nom AS Eleve_nom,
            p.prenom AS Eleve_prenom,
            g.idGroupe,
            g.nom AS Groupe_nom
        FROM Personne p
        JOIN EtudiantGroupe eg ON p.idPersonne = eg.idEtudiant
        JOIN Groupe g ON eg.idGroupe = g.idgroupe
        WHERE g.idSAE = :idSAE
        ORDER BY g.idGroupe, p.nom, p.prenom;
        ";
    
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam(":idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
    

    function getElevesSansGroupe($idSAE) {
        $req = "
        SELECT DISTINCT
            p.idPersonne,
            p.nom AS Eleve_nom,
            p.prenom AS Eleve_prenom
        FROM Personne p
        LEFT JOIN EtudiantGroupe eg ON p.idPersonne = eg.idEtudiant
        WHERE eg.idGroupe IS NULL
        AND EXISTS (
            SELECT 1
            FROM EleveInscritSae esi
            WHERE esi.idEleve = p.idPersonne
            AND esi.idSAE = :idSAE
        )
        ORDER BY p.nom, p.prenom;
        ";
    
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam(":idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    function getAllIntervenantbyAllSaebyProf($idPersonne) {
        $req = "
        SELECT 
            s.idSAE,
            s.nomSae AS SAE_nom,
            p.idPersonne AS Intervenant_id,
            p.nom AS Intervenant_nom,
            p.prenom AS Intervenant_prenom
        FROM SAE s
        LEFT JOIN ResponsablesSAE rs ON s.idSAE = rs.idSAE
        LEFT JOIN IntervenantSAE i ON s.idSAE = i.idSAE
        LEFT JOIN Personne p ON i.idIntervenant = p.idPersonne
        WHERE s.idResponsable = :idPersonne OR rs.idResp = :idPersonne
        ORDER BY p.nom, p.prenom;
        ";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function creerNotePourUneSoutenance($idSoutenance) {
        $evalReq = "
            INSERT INTO Evaluation (nom, coef)
            VALUES ('nom à définir', 1)
        ";
        $evalInsert = self::$bdd->prepare($evalReq);
        $evalInsert->execute();
        $idEval = self::$bdd->lastInsertId(); // récupérer l'idEval généré juste avant
    
        $notesReq = "
            INSERT INTO NotesSoutenance (idEval, idEleve, idSoutenance, note)
            SELECT 
                :idEval, 
                EleveInscritSae.idEleve, 
                :idSoutenance, 
                NULL
            FROM EleveInscritSae
            JOIN Soutenance ON Soutenance.idSAE = EleveInscritSae.idSAE
            WHERE Soutenance.idSoutenance = :idSoutenance
        ";
        $insertNotes = self::$bdd->prepare($notesReq);
        $insertNotes->bindParam("idEval", $idEval, PDO::PARAM_INT);
        $insertNotes->bindParam("idSoutenance", $idSoutenance, PDO::PARAM_INT);
        $insertNotes->execute();
    }
    
    function MettreAJourInfoUneEval($idEval, $noteNom, $coef,$intervenants){
        if($intervenants[0]== ""){
            $idIntervenant = null;
        }else{
            $idIntervenant = $intervenants[0];
        }
        $req = "UPDATE Evaluation SET nom = :noteNom, coef = :coef, IntervenantEvaluateur = :idIntervenant WHERE idEval = :idEval";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idEval", $idEval, PDO::PARAM_INT);
        $pdo_req->bindParam("noteNom", $noteNom, PDO::PARAM_STR);
        $pdo_req->bindParam("coef", $coef, PDO::PARAM_INT);
        $pdo_req->bindParam("idIntervenant", $idIntervenant, PDO::PARAM_INT);
        $pdo_req->execute();
    }

    function MettreAJourLesNotes($notes){
        $req = "UPDATE NotesSoutenance SET note = :note WHERE idEval = :idEval AND idEleve = :idEleve";
        $pdo_req = self::$bdd->prepare($req);
        foreach ($notes as $note) {
            $pdo_req->bindParam("idEval", $note['idEval'], PDO::PARAM_INT);
            $pdo_req->bindParam("idEleve", $note['idEleve'], PDO::PARAM_INT);
            $pdo_req->bindParam("note", $note['note'], PDO::PARAM_INT);
            $pdo_req->execute();
        }
    }

    function supprimerEval($idEval){
        // supprime les notes associé à l'évaluation
        $reqNotes = "DELETE FROM NotesSoutenance WHERE idEval = :idEval";
        $pdo_reqNotes = self::$bdd->prepare($reqNotes);
        $pdo_reqNotes->bindParam("idEval", $idEval, PDO::PARAM_INT);
        $pdo_reqNotes->execute();

        //supprime mauntenant l'évaluation
        $reqEval = "DELETE FROM Evaluation WHERE idEval = :idEval";
        $pdo_reqEval = self::$bdd->prepare($reqEval);
        $pdo_reqEval->bindParam("idEval", $idEval, PDO::PARAM_INT);
        $pdo_reqEval->execute();
    }
    
    
    
    
    
    
    
    
    
}
