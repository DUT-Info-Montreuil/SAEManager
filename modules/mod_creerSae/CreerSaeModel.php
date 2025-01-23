<?php

require_once 'modules/mod_sae/SaeModel.php';
class CreerSaeModel extends Connexion
{
    public function createSae($nomSae, $semestre, $sujet, $coResponsables, $intervenants, $eleves){
        try {
            self::$bdd->beginTransaction();


            $req = "INSERT INTO SAE (idSAE,nomSae,anneeUniversitaire,semestreUniversitaire, sujet,dateModificationSujet,idResponsable) VALUES (DEFAULT,:nomSae,:annee, :semestre, :sujet,:dateDuJour,:idResponsable)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                ':nomSae' => $nomSae,
                ':annee' => date("Y"),
                ':semestre' => $semestre,
                ':sujet' => $sujet,
                ':dateDuJour' => date("Y-m-d H:i"),
                ':idResponsable'=> $_SESSION['idUtilisateur']
            ]);
            $saeId = self::$bdd->lastInsertId();


            foreach ($coResponsables as $coResponsableId) {
                $req = "INSERT INTO ResponsablesSAE (idSAE, idResp) VALUES (:idSAE, :idResponsable)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idResponsable' => $coResponsableId
                ]);
            }


            foreach ($intervenants as $intervenantId) {
                $req = "INSERT INTO IntervenantSAE (idSAE, idIntervenant) VALUES (:idSAE, :idIntervenant)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idIntervenant' => $intervenantId
                ]);
            }


            foreach ($eleves as $eleveId) {
                $req = "INSERT INTO EleveInscritSae (idSAE, idEleve) VALUES (:idSAE, :idEleve)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idEleve' => $eleveId
                ]);
            }

            self::$bdd->commit();
            foreach($coResponsables as $coResponsable)
                SaeModel::creeNotification($coResponsable, "Vous avez été assigné co-responsable à une SAE qui vient d'être créée.", null, "index.php?module=sae&action=home");
            foreach($intervenants as $intervenant)
                SaeModel::creeNotification($intervenant, "Vous avez été assigné intervenant à une SAE qui vient d'être créée.", null, "index.php?module=sae&action=home");

            return true;
        } catch (Exception $e) {
            self::$bdd->rollBack();
            echo("Erreur lors de la création de la SAE : " . $e->getMessage());
            return false;
        }



    }

    public function recupListePersonneSansMoi(){
        $req = "SELECT * FROM Personne
                WHERE idPersonne != :idPersonne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->bindValue(":idPersonne", $_SESSION['idUtilisateur']);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupListePersonne(){
        $req = "SELECT * FROM Personne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
}
