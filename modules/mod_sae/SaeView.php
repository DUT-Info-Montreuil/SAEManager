<?php

class SaeView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    // Home

    function initSaePage($saes)
    {
        echo <<<HTML
    <div class="container mt-5">
        <h1 class="fw-bold">LISTE DES SAÉ(S)</h1>
        <div class="card shadow bg-white rounded min-h75">
            <div class="d-flex align-items-center p-5 mx-5">
                <div class="me-3">
                    <svg width="35" height="35">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                </div>
                <h3 class="fw-bold">Liste des SAÉs auxquelles vous êtes inscrit(e) :</h3>
            </div>
            <div class="sae-list">
HTML;
        foreach ($saes as $sae) {
            echo $this->lineSae($sae);
        }

        echo <<<HTML
            </div>
        </div>
    </div>
HTML;
    }

    function lineSae($sae)
    {
        $nom = htmlspecialchars($sae['nomSae']);
        $idSAE = htmlspecialchars($sae['idSAE']);

        return <<<HTML
        <div class="px-5 mx-5 my-3">
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning mx-2 w-25px h-25px"></div>
                <span class="fw-bold mx-1">$nom</span>
            </div>
            <a href="index.php?module=sae&action=details&id=$idSAE" class="text-primary text-decoration-none">Accéder à la SAE</a>
            </div>
        </div>

        HTML;
    }

    // Détails

    function initSaeDetails($sae, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer, $ressources)
    {

        $nom = htmlspecialchars($sae[0]['nomSae']);
        $dateModif = htmlspecialchars($sae[0]['dateModificationSujet']);
        $sujet = htmlspecialchars($sae[0]['sujet']);
        $idSAE = htmlspecialchars($sae[0]['idSAE']);

        echo <<<HTML
    <div class="container mt-5">
        <h1 class="fw-bold">$nom</h1>
        <div class="card shadow bg-white rounded p-4">
            <!-- Sujet -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Sujet
                </h3>
HTML;
        if ($_SESSION['estProfUtilisateur']) {
            echo <<<HTML
                <div class="d-flex align-items-center gap-2">
                    <span class="btn btn-link btn-sm shadow-none p-0 text-decoration-none" id="edit-sujet">Modifier le sujet</span>
                    <form method="POST" action="index.php?module=sae&action=supprimerSujet&id=$idSAE" style="margin: 0;">
                        <button type="submit" class="btn btn-link btn-sm shadow-none p-0 text-decoration-none">Supprimer</button>
                    </form>
                </div>
            HTML;
        }
        echo '<p class="text-muted">Posté le ' . $dateModif . '</p>';
        echo '<p>' . $sujet . '</p>';
        echo $this->popUpDepot("Rendu", $idSAE);
        echo $this->popUpDepot("Support", $idSAE);
        echo $this->popUpCreateRendu($idSAE);
        echo $this->popUpModifierRendu($idSAE);
        echo $this->popUpCreateSoutenance($idSAE);
        echo $this->popUpModifierSoutenance($idSAE);
        echo $this->popUpCreateChamp($idSAE);
        echo $this->popUpModifierSujet($idSAE, $sae);
        echo $this->popUpCreateRessource($idSAE);
        echo $this->popUpAjouterRessource($idSAE, $ressources, $ressource);
        echo <<<HTML
            </div>
HTML;

        // Champs
        echo <<<HTML
        <!-- Champ(s) -->
        <div class="mb-5">
            <h3 class="fw-bold d-flex align-items-center">
                <svg class="me-2" width="25" height="25">
                    <use xlink:href="#arrow-icon"></use>
                </svg>
                Champ(s)
            </h3>
            <div class="d-flex flex-column">
HTML;
        if (!empty($champs)) {
            foreach ($champs as $c) {
                $nomChamps = htmlspecialchars($c['nomchamp']);
                echo $this->lineChamp($nomChamps, $c['idChamps'], !in_array($c['idChamps'], $repId), $idSAE);
            }
        } else {
            echo $this->lineChamp("default", "", "", "");
        }

        if ($_SESSION['estProfUtilisateur']) {

            echo <<<HTML
            <div class=" p-3">
                <button class="btn btn-secondary rounded-pill shadow-sm px-4 p-3" id="create-champ">Ajouter un champ</button>
            </div>
HTML;
        }
        echo <<<HTML
            </div>
        </div>
HTML;

        // Ressources
        echo <<<HTML
            <!-- Ressource(s) -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Ressource(s)
                </h3>
                <div class="d-flex flex-column">
HTML;
        if (!empty($ressource)) {
            foreach ($ressource as $r) {
                $nomRessource = htmlspecialchars($r['nom']);
                $idSAE = htmlspecialchars($sae[0]['idSAE']);
                $idRessource = htmlspecialchars($r['idRessource']);
                $contenue = htmlspecialchars($r['contenu']);
                echo $this->lineRessource($nomRessource, $idSAE, $idRessource, $contenue);
            }
        } else {
            echo $this->lineRessource("default", $sae[0]['idSAE'], "", "");
        }

        if ($_SESSION['estProfUtilisateur']) {

            echo <<<HTML
            <div class="d-flex gap-3 p-3">
                <button class="btn btn-secondary rounded-pill shadow-sm px-4 p-3" id="btn-add-ressource">Ajouter une ressource</button>
                <button class="btn btn-secondary rounded-pill shadow-sm px-4" id="create-ressource">Créer une ressource</button>
            </div>
HTML;
        }
        echo <<<HTML
                </div>
            </div>
HTML;



        // Rendus
        echo <<<HTML
            <!-- Rendu(s) -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Rendu(s)
                </h3>
                <div class="d-flex flex-column">
HTML;
        if (!empty($rendus)) {
            foreach ($rendus as $r) {
                $nomRendu = htmlspecialchars($r['nom']);
                $dateLimite = htmlspecialchars($r['dateLimite']);
                $idRendu = htmlspecialchars($r['idRendu']);
                $aDeposerRendu = false;
                $listeRenduDeposer = array_keys($rendusDeposer);
                foreach ($listeRenduDeposer as $renduDeposer)
                    if ($renduDeposer == $idRendu) {
                        $aDeposerRendu = true;
                        $dateLimite = $rendusDeposer[$idRendu];
                    }

                echo $this->lineRendus($nomRendu, $dateLimite, $idRendu, $aDeposerRendu);
            }
        } else {
            echo $this->lineRendus("default", "default", 0, false);
        }

        if ($_SESSION['estProfUtilisateur']) {

            echo <<<HTML
            <div class="p-3">
                <button class="btn btn-secondary rounded-pill shadow-sm px-4 p-3" id="create-rendu">Créer un rendu</button>
            </div>
HTML;
        }
        echo <<<HTML
                </div>
            </div>
HTML;

        // Soutenances
        echo <<<HTML
            <!-- Soutenance(s) -->
            <div>
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Soutenance(s)
                </h3>
                <div class="d-flex flex-column">
HTML;
        if (!empty($soutenance)) {
            foreach ($soutenance as $s) {
                $titre = htmlspecialchars($s['titre'] ?? "default");
                $dateSoutenance = htmlspecialchars($s['date'] ?? "default");
                $salle = htmlspecialchars($s['salle'] ?? "default");
                $idSoutenance = htmlspecialchars($s['idSoutenance'] ?? "default");
                echo $this->lineSoutenance($titre, $dateSoutenance, $salle, $idSoutenance);
            }
        } else {
            echo $this->lineSoutenance("default", "default", "default", "default");
        }

        if ($_SESSION['estProfUtilisateur']) {

            echo <<<HTML
            <div class="p-3">
                <button class="btn btn-secondary rounded-pill shadow-sm px-4 p-3" id="create-soutenance">Créer une soutenance</button>
            </div>
HTML;
        }

        echo <<<HTML
                </div>
            </div>
        </div>
        <script src="js/navbar/saeview.js"></script>
    </div>
HTML;
    }

    function lineChamp($nomChamp, $idChamps, $param, $idSAE)
    {
        if ($nomChamp == "default") {
            return <<<HTML
        <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
            <span class="text-muted">Aucun champ disponible</span>
        </div>
        HTML;
        }

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
        <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
            <span>$nomChamp</span>
            <div class="ms-auto d-flex gap-2">
                <form method="POST" action="index.php?module=sae&action=supprimerChamp&id=$idChamps" style="margin: 0;">
                    <button type="submit" class="btn btn-secondary btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
        HTML;
        }

        if ($param) {
            $area = '<textarea name="reponse' . $idChamps . '" class="form-control mb-3 w-75" placeholder="Écrire ici..." rows="4"></textarea>';
            $input = '<button type="submit" class="btn btn-primary ms-auto">Envoyer</button>';
        } else {
            $area = '<label class="text-warning">Ce champ a déjà été rendu</label>';
            $input = '';
        }

        return <<<HTML
    <form class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2" method="POST" action="index.php?module=sae&action=ajout_champ&id=$idSAE&idchamp=$idChamps">
        <div class="d-flex flex-column w-100">
            <span class="mb-2">$nomChamp</span>
            $area
        </div>
        $input
    </form>
    HTML;
    }





    function lineRessource($nomRessource, $idSAE, $idRessource, $contenue)
    {

        if ($nomRessource == "default") {
            return <<<HTML
        <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
            <span>Aucune ressource disponible</span>
        </div>
        HTML;
        }

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <span>$nomRessource</span>
                <div class="ms-auto d-flex gap-2">
                    <form method="POST" action="index.php?module=sae&action=supprimerRessource&id=$idSAE&idRessource=$idRessource" class="m-0">
                        <button type="submit" class="btn btn-secondary btn-sm">Supprimer</button>
                    </form>
                    <a href="#" class="text-decoration-none text-primary align-self-center">Ouvrir la ressource</a>  <!-- TO-DO : Ajouter un lien pour voir le contenu -->
                </div>
            </div>
            HTML;
        }

        return <<<HTML
    <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
        <span>$nomRessource</span> 
        <a href="#" class="ms-auto text-decoration-none text-primary">Ouvrir la ressource</a> <!-- TO-DO : Ajouter un lien pour voir le contenu -->
    </div>
    HTML;
    }

    function lineRendus($nom, $dateLimite, $id, $renduDeposer)
    {

        if ($nom == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucun rendu disponible</span>
                </div>
        HTML;
        }

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
            <div class="d-flex align-items-center bg-light rounded-3 shadow-sm mb-2 px-2">
                <span>$nom</span>
                <div class="ms-auto p-3 d-flex gap-2">
                    <span class="btn btn-secondary btn-sm modalModifierRendu$id" id="edit-rendu-btn" data-bs-toggle="modal">Modifier</span>
                    <form method="POST" action="index.php?module=sae&action=supprimerRendu&id=$id" style="margin: 0;">
                        <button type="submit" class="btn btn-secondary btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
            HTML;
        }

        if ($renduDeposer) {
            $color = "success";
            $phrase = "Déposer le : ";
        } else {
            $color = "danger";
            $phrase = "A déposer avant le : ";
        }


        return <<<HTML

        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$nom</p>
                </div>
                <div class="text-end">
                <p class="text-$color mb-0">$phrase $dateLimite</p>
                    <a href="#" class="text-primary text-decoration-none fw-bold rendudrop-$id">Déposer le rendu</a>
                </div>
            </div>

        HTML;
    }

    function lineSoutenance($titre, $dateSoutenance, $salle, $idSoutenance)
    {

        if ($titre == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucune soutenance disponible</span>
                </div>
        HTML;
        }

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <span>$titre</span>
                <div class="ms-auto d-flex gap-2">
                    <span class="btn btn-secondary btn-sm modalModifierSoutenance$idSoutenance" id="edit-soutenance-btn" data-bs-toggle="modal">Modifier</span>
                    <form method="POST" action="index.php?module=sae&action=supprimerSoutenance&id=$idSoutenance" style="margin: 0;">
                        <button type="submit" class="btn btn-secondary btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
            HTML;
        }


        return <<<HTML
    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-2">
        <div class="d-flex align-items-center">
            <p class="mb-0">$titre</p>
        </div>
        <div class="text-end">
            <p class="text-muted mb-0">Votre date de passage : $dateSoutenance</p>
            <p class="text-muted mb-1">Salle : $salle</p>
            <a href="#" class="text-primary text-decoration-none fw-bold supportdrop-$idSoutenance">Déposer un support</a>
        </div>
    </div>
    HTML;
    }


    /* Groupes */

    function initGroupPage($sae, $groupe, $responsable)
    {

        foreach ($sae as $s) {
            $nomSAE = htmlspecialchars($s['nomSae']);
        }

        echo <<<HTML
    <div class="container mt-5">
        <h1 class="fw-bold">$nomSAE</h1>
        <div class="card shadow bg-white rounded p-4 min-h75">
            <!-- MEMBRE DU GROUPE -->
            <div class="mb-5">
                <h3 class="d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Membre du groupe
                </h3>
                <div class="d-flex flex-wrap">
HTML;
        foreach ($groupe as $membre) {
            $nom = htmlspecialchars($membre['nom']);
            $prenom = htmlspecialchars($membre['prenom']);
            echo $this->linePersonne($prenom, $nom);
        }
        echo <<<HTML
                </div>
            </div>

            <!-- RESPONSABLE -->
            <div class="mb-5">
                <h3 class="d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Responsable(s) et intervetant(e)
                </h3>
                <div class="d-flex flex-wrap">
HTML;
        foreach ($responsable as $resp) {
            $nom = htmlspecialchars($resp['nom']);
            $prenom = htmlspecialchars($resp['prenom']);
            echo $this->linePersonne($prenom, $nom);
        }
        echo <<<HTML
                </div>
            </div>

            <!-- MESSAGE -->
            <div class="mb-5">
                <h3 class="d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Message(s) du groupe
                </h3>
                <div class="d-flex flex-column">
                </div>
            </div>

        </div>
    </div>
    HTML;
    }

    function linePersonne($prenom, $nom)
    {
        return <<<HTML
    <div class="px-3 my-3 w-200px">
        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
            <div class="rounded-circle bg-warning mx-2 w-25px h-25px"></div>
                <span class="fw-bold mx-1">$prenom $nom</span>
            </div>
        </div>
    </div>
HTML;
    }

    // Notes

    function initNotePage($notes, $sae, $noteSoutenance)
    {
        foreach ($sae as $s) {
            $nom = htmlspecialchars($s['nomSae']);
        }

        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">$nom</h1>
            <div class="card shadow bg-white rounded p-4 min-h75">
                <!-- Notes des rendus -->
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Notes rendu(s)
                    </h3>
                    <div>
    HTML;

        $totalRendus = 0;
        $totalCoeffRendus = 0;

        if (!empty($notes)) {
            foreach ($notes as $note) {
                $nom = htmlspecialchars($note['nom']);
                $noteValue = htmlspecialchars($note['note']);
                $coeff = htmlspecialchars($note['coeff']);

                $totalRendus += $noteValue * $coeff;
                $totalCoeffRendus += $coeff;

                echo $this->lineNoteRendus($nom, $noteValue, $coeff);
            }
        } else {
            echo $this->lineNoteRendus("default", "default", "default");
        }

        $totalSoutenance = 0;
        $totalCoeffSoutenance = 0;

        echo <<<HTML
                    </div>
                </div>
    
                <!-- Notes de soutenance -->
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Notes soutenance(s)
                    </h3>
    HTML;

        if (!empty($noteSoutenance)) {
            foreach ($noteSoutenance as $note) {
                $nom = htmlspecialchars($note['titre']);
                $noteValue = htmlspecialchars($note['note']);
                $coeff = htmlspecialchars($note['coeff']);

                $totalSoutenance += $noteValue * $coeff;
                $totalCoeffSoutenance += $coeff;

                echo $this->lineNoteRendus($nom, $noteValue, $coeff);
            }
        } else {
            echo $this->lineNoteRendus("default", "default", "default");
        }

        $totalNotes = $totalRendus + $totalSoutenance;
        $totalCoeff = $totalCoeffRendus + $totalCoeffSoutenance;

        $moyenne = ($totalCoeff > 0) ? (round($totalNotes / $totalCoeff, 1)) : 0;

        echo $this->lineMoyenne($moyenne);

        echo <<<HTML
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }



    function lineNoteRendus($nom, $notes, $coeff)
    {

        if ($nom == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucune note disponible</span>
                </div>
        HTML;
        }
        return <<<HTML

        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="fw-bold mb-0">$nom</p>
                </div>
                <div class="text-end">
                <p class="text-success mb-0">Note : $notes, Coeff : $coeff</p>
                </div>
            </div>

        HTML;
    }

    function lineMoyenne($moyenne)
    {


        return <<<HTML
        <div class="d-flex align-items-center justify-content-between p-4 bg-light rounded-3 shadow-sm mb-2 my-5">
                <div class="d-flex align-items-center">
                    <p class="fw-bold mb-0 fs-5">Moyenne de la SAE</p>
                </div>
                <div class="text-end">
                <p class="mb-0">$moyenne / 20</p>
                </div>
            </div>

        HTML;
    }

    // Pop up pour les dépots / rendus
    // TO-DO : lié une action au form vers le controller SAE, pour ensuite insérer dans la base de données le dépot + placer le fichier
    function popUpDepot($typeDepot, $idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalDepot$typeDepot">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Déposer un $typeDepot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=ajoutDepot$typeDepot&id=$idSae" method="POST" id="fileUploadForm" enctype="multipart/form-data">
                        <input type="hidden" id="idSaeDepot$typeDepot" name="idSaeDepot$typeDepot" value="">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="card border rounded-3 mb-3">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <svg class="icon" width="100" height="100" style="fill: #0AF;">
                                        <use xlink:href="#upload-icon"></use>
                                    </svg>
                                    <p>Déposer ou glisser un fichier ici</p>
                                    <p class="fs-10 fw-light">Taille max : 20 Mo</p>
                                    <input type="file" class="form-control-file" id="fileInput$typeDepot" name="fileInput$typeDepot" required style="display: none;">
                                    <button type="button" class="btn btn-light w-50" id="selectFileButton$typeDepot">Sélectionner fichier</button>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" id="depotCancelButton$typeDepot" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
HTML;
    }

    function popUpCreateRendu($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalCreateRendu">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Créer un rendu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=createRendu&id=$idSae" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="titreRendu" id="titreRendu" placeholder="Titre du rendu" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="date" class="form-control" name="dateLimiteRendu" id="dateLimiteRendu" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="time" class="form-control" name="heureLimiteRendu" id="heureLimiteRendu" required>
                            </div>
                            <div class="form-group mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="renduNote" id="renduNote">
                                <label class="form-check-label" for="renduNote">Rendu noté</label>
                            </div>
                            <div class="form-group mb-3" id="coeffInput" style="display: none;">
                                <input type="number" class="form-control" name="coeff" id="coeff" placeholder="Coefficient" min="1" max="10">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-rendu-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    HTML;
    }

    function popUpCreateRessource($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalCreateRessource">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Créer une ressource</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=createRessource&id=$idSae" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="nomRessource" id="nomRessource" placeholder="Nom de la ressource" required>
                            </div>
                            <div class="form-group mb-3">
                                <textarea class="form-control" name="contenuRessource" id="contenuRessource" placeholder="Contenu de la ressource" required></textarea>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-ressource-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpAjouterRessource($idSae, $ressources, $ressourcesAssociees)
    {
        $options = '';

        $idsRessourcesAssociees = array_map(fn($r) => $r['idRessource'], $ressourcesAssociees);

        foreach ($ressources as $ressource) {
            if (!in_array($ressource['idRessource'], $idsRessourcesAssociees)) {
                $idRessource = htmlspecialchars($ressource['idRessource']);
                $nomRessource = htmlspecialchars($ressource['nom']);
                $options .= "<option value=\"$idRessource\">$nomRessource</option>";
            }
        }

        if (empty($options)) {
            $options = '<option value="" disabled>Aucune ressource disponible</option>';
        }

        return <<<HTML
    <div class="modal" tabindex="-1" id="modalAjouterRessource">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder text-center w-100">Ajouter une ressource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?module=sae&action=ajouterRessource&id=$idSae" method="POST">
                    <div class="modal-body d-flex flex-column text-center">
                        <div class="form-group mb-3">
                            <label for="ressourceSelect">Sélectionner une ressource</label>
                            <select class="form-control" id="ressourceSelect" name="ressourceSelect" required>
                                $options
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success m-3">Valider</button>
                            <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-ressource-add-cancel">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    HTML;
    }

    function popUpCreateChamp($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalCreateChamp">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Créer un champ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=createChamp&id=$idSae" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="nomChamp" id="nomChamp" placeholder="Nom du champ" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-champ-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpCreateSoutenance($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalCreateSoutenance">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Créer une soutenance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=createSoutenance&id=$idSae" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="titreSoutenance" id="titreSoutenance" placeholder="Titre de la soutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="salleSoutenance" id="salleSoutenance" placeholder="Salle de la soutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="date" class="form-control" name="dateSoutenance" id="dateSoutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="time" class="form-control" name="heureSoutenance" id="heureSoutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="number" class="form-control" name="dureeSoutenance" id="dureeSoutenance" placeholder="Durée (en minutes)" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-soutenance-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpModifierSoutenance($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalModifierSoutenance">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Modifier la soutenance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=modifierSoutenance&id=$idSae" method="POST">
                        <input type="hidden" id="idSoutenanceAModifier" name="idSoutenanceAModifier" value="">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="titreSoutenance" id="titreSoutenance" placeholder="Titre de la soutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="salleSoutenance" id="salleSoutenance" placeholder="Salle de la soutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="date" class="form-control" name="dateSoutenance" id="dateSoutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="time" class="form-control" name="heureSoutenance" id="heureSoutenance" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="number" class="form-control" name="dureeSoutenance" id="dureeSoutenance" placeholder="Durée (en minutes)" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-soutenance-edit-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpModifierRendu($idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalModifierRendu">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Modifier le rendu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=modifierRendu&id=$idSae" method="POST">
                    <input type="hidden" id="idRenduAModifier" name="idRenduAModifier" value="">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="titreRendu" id="titreRendu" placeholder="Titre du rendu" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="date" class="form-control" name="dateLimiteRendu" id="dateLimiteRendu" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="time" class="form-control" name="heureLimiteRendu" id="heureLimiteRendu" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-rendu-edit-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpModifierSujet($idSae, $sae)
    {

        $sujet = htmlspecialchars($sae[0]['sujet']);
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalModifierSujet">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100" id="edit-sujet-btn">Modifier le sujet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=modifierSujet&id=$idSae" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <textarea class="form-control" name="sujet" id="sujet" placeholder="Modifier le sujet" required>$sujet</textarea>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-sujet-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }


    // A continuer lorsque laidRendu page "Cloud" des SAE sera réalisé.
    function ajouterFichierCloud($idSae)
    {
        return <<<HTML
        <div class="d-block modal" tabindex="-1" id="modalAjouterFichierCloud">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Déposer un fichier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=uploadFichier&id=$idSae" id="fileUploadForm" method="POST" enctype="multipart/form-data">
                        <div class="modal-body d-flex flex-column text-center">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="fileName" id="fileName" placeholder="Entrer le nom du fichier">
                            <small class="form-text text-muted">Veilliez à choisir un nom qui n'existe pas déjà dans le cloud de cette SAE.</small>
                        </div>
                            <input type="color" class="form-control form-control-color" name="colorChoice" id="colorChoice" value="#563d7c" title="Choisissez votre couleur">
                            <div class="card border rounded-3 mb-3">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <svg class="icon" width="100" height="100" style="fill: #0AF;">
                                        <use xlink:href="#upload-icon"></use>
                                    </svg>
                                    <p>Déposer ou glisser un fichier ici</p>
                                    <p class="fs-10 fw-light">Taille max : 20 Mo</p>
                                    <input type="file" class="form-control-file" name="fileInput" id="fileInput" required style="display: none;">
                                    <button type="button" class="btn btn-light w-50" id="selectFileButton">Sélectionner fichier</button>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" id="addFileToCloudCancelButton" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
HTML;
    }
}
