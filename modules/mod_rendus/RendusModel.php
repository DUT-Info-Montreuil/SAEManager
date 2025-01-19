<?php

class RendusModel extends Connexion
{
    function getRendusByPersonne($idPersonne)
    {
        $req = "SELECT distinct(Rendu.nom) AS Rendu_nom, SAE.nomSae AS SAE_nom, Rendu.dateLimite, SAE.idSAE
                FROM Personne
                INNER JOIN EtudiantGroupe ON EtudiantGroupe.idEtudiant = Personne.idPersonne
                INNER JOIN Groupe ON Groupe.idGroupe = EtudiantGroupe.idGroupe
                INNER JOIN SAE ON SAE.idSAE = Groupe.idSAE
                INNER JOIN Rendu ON Rendu.idSAE = SAE.idSAE
                WHERE Personne.idPersonne = :idPersonne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
}
