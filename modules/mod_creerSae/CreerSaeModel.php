<?php

class CreerSaeModel extends Connexion
{
    public function createSae($nomSae, $semestre, $sujet, $coResponsables, $intervenants, $eleves){
        try {
            self::$bdd->beginTransaction();

            // Insertion de la SAE principale
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
            $saeId = self::$bdd->lastInsertId(); // Récupérer l'ID de la SAE créée

            // Insertion des co-responsables
            foreach ($coResponsables as $coResponsableId) {
                $req = "INSERT INTO ResponsablesSAE (idSAE, idResp) VALUES (:idSAE, :idResponsable)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idResponsable' => $coResponsableId
                ]);
            }

            // Insertion des intervenants
            foreach ($intervenants as $intervenantId) {
                $req = "INSERT INTO IntervenantSAE (idSAE, idIntervenant) VALUES (:idSAE, :idIntervenant)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idIntervenant' => $intervenantId
                ]);
            }

            // Insertion des élèves
            foreach ($eleves as $eleveId) {
                $req = "INSERT INTO EleveInscritSae (idSAE, idEleve) VALUES (:idSAE, :idEleve)";
                $stmt = self::$bdd->prepare($req);
                $stmt->execute([
                    ':idSAE' => $saeId,
                    ':idEleve' => $eleveId
                ]);
            }

            self::$bdd->commit();
            return true;
        } catch (Exception $e) {
            self::$bdd->rollBack();
            echo("Erreur lors de la création de la SAE : " . $e->getMessage());
            return false;
        }
    }
    public function recupListePersonne(){
        $req = "SELECT * FROM Personne";
        $pdo_req = self::$bdd->prepare($req);
        $pdo_req->execute();
        return $pdo_req->fetchAll(PDO::FETCH_ASSOC);
    }
}
