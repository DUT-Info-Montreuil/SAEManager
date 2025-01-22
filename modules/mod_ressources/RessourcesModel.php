
<?php

class RessourcesModel extends Connexion
{

    public function getRessource()
    {
        $req = "SELECT * FROM Ressource";
        $pdo_req = self::$bdd->prepare($req);
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
}
