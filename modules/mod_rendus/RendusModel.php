<?php

class RendusModel extends Connexion{

    // GET

    function getRendusByPersonne($idPersonne){
        // $req = "SELECT Rendu.nom AS Rendu_nom, SAE.nomSae AS SAE_nom, Rendu.dateLimite, SAE.idSAE
        //         FROM Personne
        //         INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = Personne.idPersonne
        //         INNER JOIN RenduGroupe ON RenduGroupe.idGroupe = EtudiantGroupe.idGroupe
        //         INNER JOIN Rendu ON Rendu.idRendu = RenduGroupe.idRendu
        //         INNER JOIN SAE ON SAE.idSAE = Rendu.idSAE
        //         WHERE Personne.idPersonne = :idPersonne";

        $req = "SELECT Rendu.nom AS Rendu_nom,SAE.nomSae AS SAE_nom,Rendu.dateLimite,Rendu.idSAE,IF(DepotDesRendus.idEleve IS NOT NULL, 1, 0) AS aRendu
                FROM EleveInscritSae
                JOIN Rendu ON EleveInscritSae.idSAE = Rendu.idSAE
                LEFT JOIN DepotDesRendus ON Rendu.idRendu = DepotDesRendus.idRendu AND EleveInscritSae.idEleve = DepotDesRendus.idEleve
                JOIN SAE ON Rendu.idSAE = SAE.idSAE
                WHERE EleveInscritSae.idEleve = :idEleve;";


        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idEleve", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getRendusProfByPersonne($idPersonne){
        $req = "SELECT DISTINCT Rendu.idRendu,Rendu.nom AS Rendu_nom,SAE.nomSae AS SAE_nom,Rendu.dateLimite,Rendu.idSAE
                FROM SAE
                JOIN Rendu ON SAE.idSAE = Rendu.idSAE
                LEFT JOIN ResponsablesSAE ON SAE.idSAE = ResponsablesSAE.idSAE
                LEFT JOIN IntervenantSAE ON SAE.idSAE = IntervenantSAE.idSAE
                WHERE SAE.idResponsable = :idPersonne OR ResponsablesSAE.idResp = :idPersonne OR IntervenantSAE.idIntervenant = :idPersonne;";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);    
    }

    function getNotesdesRendusProfByPersonne($idPersonne) {
        $req = "
        SELECT DISTINCT 
            r.idRendu,
            r.nom AS Rendu_nom,
            r.dateLimite AS Rendu_dateLimite,
            s.nomSae AS SAE_nom,
            e.idEval,
            e.nom AS Eval_nom,
            e.coef AS Eval_coef,
            n.note AS Note_valeur,
            CASE 
                WHEN s.idResponsable = :idPersonne THEN 1
                WHEN rs.idResp = :idPersonne THEN 1
                WHEN e.IntervenantEvaluateur = :idPersonne THEN 1
                ELSE 0 
            END AS PeutEvaluer
        FROM Rendu r
        JOIN SAE s ON r.idSAE = s.idSAE
        LEFT JOIN Notes n ON r.idRendu = n.idRendu
        LEFT JOIN Evaluation e ON n.idEval = e.idEval
        LEFT JOIN ResponsablesSAE rs ON s.idSAE = rs.idSAE
        LEFT JOIN IntervenantSAE i ON s.idSAE = i.idSAE
        WHERE 
            s.idResponsable = :idPersonne 
            OR rs.idResp = :idPersonne
            OR i.idIntervenant = :idPersonne
            OR e.IntervenantEvaluateur = :idPersonne
        ORDER BY r.idRendu, e.idEval;
        ";
    
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
    
    
    
    
    
}
