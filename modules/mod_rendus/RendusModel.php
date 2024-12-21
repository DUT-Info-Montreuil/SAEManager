<?php

class RendusModel extends Connexion
{

    // GET

    function getRendusByPersonne($idPersonne)
    {
        $req = "SELECT rendu.nom AS rendu_nom, sae.nom AS sae_nom, rendu.dateLimite, sae.idSAE
                FROM personne
                INNER JOIN etudiantgroupe ON etudiantgroupe.idEtudiant = personne.idPersonne
                INNER JOIN rendugroupe ON rendugroupe.idGroupe = etudiantgroupe.idGroupe
                INNER JOIN rendu ON rendu.idRendu = rendugroupe.idRendu
                INNER JOIN sae ON sae.idSAE = rendu.idSAE
                WHERE personne.idPersonne = :idPersonne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
}
