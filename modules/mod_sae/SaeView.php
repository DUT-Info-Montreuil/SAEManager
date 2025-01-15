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

    function initSaeDetails($sae, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer, $supportsDeposer)
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
        echo '<p class="text-muted">Posté le ' . $dateModif . '</p>';
        echo '<p>' . $sujet . '</p>';
        echo $this->popUpDepot("Rendu", $idSAE);
        echo $this->popUpDepot("Support", $idSAE); 
        echo $this->popUpSupressionDepot("Rendu", $idSAE);
        echo $this->popUpSupressionDepot("Support", $idSAE);
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
                echo $this->lineChamp($nomChamps, $c['idChamps'], !in_array($c['idChamps'], $repId));
            }
        } else {
            echo $this->lineChamp("default","", "");
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
                $nomRessource = htmlspecialchars($r['contenu']);
                echo $this->lineRessource($nomRessource);
            }
        } else {
            echo $this->lineRessource("default");
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
                foreach($listeRenduDeposer as $renduDeposer)
                    if($renduDeposer == $idRendu){
                        $aDeposerRendu = true;
                        $dateLimite = $rendusDeposer[$idRendu];
                    }

                echo $this->lineRendus($nomRendu, $dateLimite, $idRendu, $aDeposerRendu);
            }
        } else {
            echo $this->lineRendus("default", "default", 0, false);
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
                $id = htmlspecialchars($s['idSoutenance'] ?? "default");
                $titre = htmlspecialchars($s['titre'] ?? "default");
                $dateSoutenance = htmlspecialchars($s['date'] ?? "default");
                $salle = htmlspecialchars($s['salle'] ?? "default");
                $supportDeposer = $supportsDeposer[$id] ?? false;
                echo $this->lineSoutenance($titre, $dateSoutenance, $salle, $id, $supportDeposer);
            }
        } else {
            echo $this->lineSoutenance("default", "default", "default", "default", "default");
        }
        echo <<<HTML
                </div>
            </div>
        </div>
        <script src="js/navbar/saeview.js"></script>
    </div>
HTML;
    }

    function lineChamp($nomChamp, $idChamps, $param)
    {

        if ($nomChamp == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucun champ disponible</span>
                </div>
        HTML;
        }
        
        $area = '<label class="text-success">Ce champ a déjà été rendu</label>';
        $input = '';
     
        if ($param){
            $area = '<textarea name="reponse'.$idChamps.'" cols="100" class="zone-texte" placeholder="Ecrire ici..."></textarea>';
            $input = '<input class="ms-auto btn btn-primary text-decoration-none" text="envoyer" type="submit"/>';
        }

        return <<<HTML
            <form class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2" method="POST" action="index.php?module=sae&action=ajout_champ&id=1&idchamp=$idChamps">
                <div class="d-flex flex-column">
                    <span>$nomChamp</span> 
                    $area
                    $input
                </div>
            </form>

        HTML;
    }

    function lineRessource($nomRessource)
    {

        if ($nomRessource == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucune ressource disponible</span>
                </div>
        HTML;
        }

        return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>$nomRessource</span>
                        <a href="#" class="ms-auto text-decoration-none text-primary">fichier.pdf</a>
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
        if($renduDeposer){
            $color = "success";
            $phrase = "Déposer le : ";
            $depotousupression = '<a href="#" class="text-primary text-danger text-decoration-none supressRenduButton-'.$id.'">Suprimmer le rendu déposer</a>';
        }
        else{
            $color = "danger";
            $phrase = "A déposer avant le : ";
            $depotousupression = '<a href="#" class="text-primary text-decoration-none fw-bold rendudrop-'.$id.'">Déposer le rendu</a>';
        }

        return <<<HTML
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$nom</p>
                </div>
                <div class="text-end">
                <p class="text-$color mb-0">$phrase $dateLimite</p>
                    <div class="d-flex flex-column">
                        $depotousupression
                    </div>
                </div>
            </div>

        HTML;
    }

    function lineSoutenance($titre, $dateSoutenance, $salle, $idSoutenance, $supportsDeposer)
    {
            
        if ($titre == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucune soutenance disponible</span>
                </div>
        HTML;
        }

        if($supportsDeposer)
            $depotousupression = '<a href="#" class="text-primary text-danger text-decoration-none supressSupportButton-'.$idSoutenance.'">Suprimmer le support déposé</a>';
        else
            $depotousupression = '<a href="#" class="text-primary text-decoration-none fw-bold rendusoutenance-'.$idSoutenance.'">Déposer un support</a>';
        

        return <<<HTML
    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
        <div class="d-flex align-items-center">
            <p class="mb-0">$titre</p>
        </div>
        <div class="text-end">
            <p class="text-muted mb-0">Votre date de passage : $dateSoutenance</p>
            <p class="text-muted mb-1">Salle : $salle</p>
            $depotousupression
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
    // Pop up pour les support / rendus
    // TO-DO : lié une action au form vers le controller SAE, pour ensuite insérer dans la base de données le dépot + placer le fichier
    function popUpDepot($typeDepot, $idSae){
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

    function popUpSupressionDepot($typeDepot, $idSae){
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalSupressionDepot$typeDepot">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Suprimmer un $typeDepot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=suprimmerDepotGroupe$typeDepot&id=$idSae" method="POST" id="fileUploadForm" enctype="multipart/form-data">
                    <input type="hidden" id="idDepotSupression$typeDepot" name="idDepotSupression$typeDepot" value="">
                        <div class="modal-body d-flex flex-column text-center">
                            <p class="h4">Etes vous sur de vouloir suprimmer votre $typeDepot ?<p>   
                            <button type="submit" class="btn btn-success mx-3 mt-4">Valider</button>
                            <button type="button" class="btn btn-danger mx-3 mt-4" id="modalSupressionDepot$typeDepot" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
HTML;
    }

    // A continuer lorsque la page "Cloud" des SAE sera réalisé.
    function ajouterFichierCloud($idSae){
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalAjouterFichierCloud">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Déposer un fichier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=uploadFichierCloud&id=$idSae" id="fileUploadForm" method="POST" enctype="multipart/form-data">
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
