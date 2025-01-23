<?php

require_once 'modules/mod_paneladmin/PanelAdminModel.php';
class PanelAdminModel extends Connexion
{

    public function getPersonneNonProf()
    {
        $req = "SELECT * FROM Personne
                WHERE estProf != 1;";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonneProf()
    {
        $req = "SELECT * FROM Personne
                WHERE estProf = 1;";

        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProfesseur($personnesID){
        foreach ($personnesID as $id){
            $req = "UPDATE Personne SET estProf = 1 WHERE idPersonne = :id;";
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindParam(':id', $id);
            $pdo_req->execute();
        }
    }

    public function delProfesseur($personnesID)
    {
        foreach ($personnesID as $id){
            $req = "UPDATE Personne SET estProf = 0 WHERE idPersonne = :id;";
            $pdo_req = self::$bdd->prepare($req);
            $pdo_req->bindParam(':id', $id);
            $pdo_req->execute();
        }
    }
}
