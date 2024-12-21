<?php

class SaeModel extends Connexion
{

    // GET

    public function getSaesByPersonneId($idPersonne)
    {
        $req = "SELECT sae.nom, sae.idSAE
                FROM personne
                INNER JOIN etudiantgroupe ON personne.idPersonne = etudiantgroupe.idEtudiant
                INNER JOIN groupe ON groupe.idGroupe = etudiantgroupe.idGroupe
                INNER JOIN sae ON sae.idSae = groupe.idSae
                WHERE personne.idPersonne = :idPersonne
                ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idPersonne", $idPersonne, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getSaeById($idSAE)
    {
        $req = "SELECT *
                FROM sae
                WHERE idSAE = :idSAE
                ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    public function getRessourceBySAE($idSAE)
    {
        $req = "SELECT contenu
                FROM ressource
                INNER JOIN ressourcessae ON ressourcessae.idRessource = ressource.idRessource
                INNER JOIN sae ON ressourcessae.idSAE = sae.idSAE
                WHERE sae.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }


    public function getRenduBySae($idSAE)
    {

        $req = "
                SELECT rendu.nom, rendu.dateLimite
                FROM rendu
                INNER JOIN sae ON sae.idSAE = rendu.idSAE
                WHERE sae.idSAE = :idSAE
        ";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }

    function getSoutenanceBySae($idSAE)
    {
        $req = "SELECT soutenance.titre, soutenance.date, soutenance.salle
                FROM soutenance
                INNER JOIN sae ON sae.idSAE = soutenance.idSAE
                WHERE sae.idSAE = :idSAE";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindParam("idSAE", $idSAE, PDO::PARAM_INT);
        $pdo_req->execute();
        return $pdo_req->fetchAll();
    }
}
