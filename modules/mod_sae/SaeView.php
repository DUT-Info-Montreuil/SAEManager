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
                <div class="d-flex flex-column">
                    <h3 class="fw-bold">Liste des SAÉs auxquelles vous êtes inscrit(e) :</h3>
    HTML;
                    if(empty($saes)){
                    echo <<<HTML
                        <p class="h5" >Vous êtes inscrit à aucunes Saés.</p>
    HTML;
                    }
        echo <<<HTML
                </div>
            </div>
HTML;
        if(!empty($saes)){
        echo <<<HTML
            <div class="sae-list">
HTML;
            foreach ($saes as $sae) {
                echo $this->lineSae($sae);
            }
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

    function initSaeDetails($pasinscrits, $groupes, $infosEtudiant, $etudiants,$profs,$sae, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer, $supportsDeposer, $ressources)
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
        echo $this->popUpSupressionDepot("Rendu", $idSAE);
        echo $this->popUpSupressionDepot("Support", $idSAE);
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
        if ($_SESSION['estProfUtilisateur']) {
            echo <<<HTML
            <div class="d-flex">
                <div class="mb-5 w-50 me-3">  
                    <h3 class="fw-bold d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Professeur(s)
                    </h3>
                    <div class="d-flex flex-column">
    HTML;
            echo $this->initAjoutProf($profs, $idSAE);
            echo <<<HTML
                    </div>
                </div>
                <div class="mb-5 w-50">
                    <h3 class="fw-bold d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Etudiants(s)
                    </h3>
                    <div class="d-flex flex-column">
    HTML;
            echo $this->initAjoutEtudiant($pasinscrits, $idSAE);
            echo <<<HTML
                    </div>
                </div>
            </div>
    HTML;
        } elseif (!$infosEtudiant['inGroupe']) {
            if ($infosEtudiant['inProposition']) {
                echo '<h1 class="my-4">Vous faites déjà partie d\'une proposition de groupe</h1>';
            } else {
                echo $this->initCreerGroupe($etudiants, $idSAE);
            }
        }

        if ($_SESSION['estProfUtilisateur']){
            // Groupes
            echo <<<HTML
                <!-- Groupe(s) -->
                <div class="mb-5">
                    <h3 class="fw-bold d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Groupe(s)
                    </h3>
                    <div class="d-flex flex-column">
    HTML;
            if (!empty($groupes)) {
                foreach($groupes as $groupe){
                    echo $this->lineGroupes($groupe, $_SESSION['idUtilisateur'], $idSAE);
                }
            } else {
                echo $this->lineGroupes("default", $etudiants, $idSAE);
            }
            echo <<<HTML
                    </div>
                </div>
    HTML;
            }

        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']){
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
            echo $this->lineChamp("default","", "", "");
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
        }
        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']){
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
        }

        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']){
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
        }
        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']){
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
                $idSoutenance = htmlspecialchars($s['idSoutenance'] ?? "default");
                $supportDeposer = $supportsDeposer[$id] ?? false;
                echo $this->lineSoutenance($titre, $dateSoutenance, $salle, $idSoutenance, $supportDeposer);
            }
        } else {
            echo $this->lineSoutenance("default", "default", "default", "default", "default");
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
HTML;
        }
        echo <<<HTML
        <script src="js/saeview.js"></script>
    </div>
HTML;
    }

    function initAjoutProf($noms, $idSAE)
    {
        $html = '<form method="POST" action="index.php?module=sae&action=ajoutProf&id=' . $idSAE . '" class="p-3 border rounded shadow-sm bg-light">';
        $html .= '<div class="mb-3">';
        $html .= '<label for="nom" class="form-label fw-bold">Ajouter un professeur à la SAE :</label>';
        $html .= '<select name="idPers" class="form-select" required>';
        $html .= '<option value="" selected>Choisissez un professeur</option>';
        foreach ($noms as $row) {
            $id = htmlspecialchars($row['idPersonne']);
            $nom = htmlspecialchars($row['nom']);
            $prenom = htmlspecialchars($row['prenom']);
            $html .= "<option value=\"$id\">$nom $prenom</option>";
        }
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="mb-3">';
        $html .= '<label for="poste" class="form-label fw-bold">Poste :</label>';
        $html .= '<select name="poste" class="form-select" required>';
        $html .= '<option value="resp">CoResponsable</option>';
        $html .= '<option value="inter">Intervenant</option>';
        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="d-flex justify-content-end">';
        $html .= '<button type="submit" class="btn btn-primary">Valider</button>';
        $html .= '</div>';
        $html .= '</form>';
        return $html;
    }

    function initAjoutEtudiant($etudiants, $idSAE)
    {
        $inputs = '';

        foreach ($etudiants as $etudiant) {
            $id = htmlspecialchars($etudiant['idPersonne']);
            $nom = htmlspecialchars($etudiant['nom']);
            $prenom = htmlspecialchars($etudiant['prenom']);
            $inputs .= '<div class="form-check">
                            <input class="form-check-input" name="student[]" type="checkbox" value="'.$id.'" id="'.$id.'">
                            <label class="form-check-label" for="'.$id.'">'.$prenom . ' ' . $nom.'</label>
                        </div>';
        }

        return <<<HTML
                <form method="POST" action="index.php?module=sae&action=ajoutEtudiantSAE&id=$idSAE" class="p-3 border rounded shadow-sm bg-light" id="studentForm">
                    <label class="form-label fw-bold">Ajouter des étudiants à la SAE :</label>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="dropdownButton">Rechercher des étudiants</button>
                        <div class="dropdown-menu p-2 w-100" id="dropdownContent" style="display: none;">
                            <!-- Barre de recherche -->
                            <input 
                                type="text" 
                                class="form-control mb-2" 
                                id="searchInput" 
                                placeholder="Rechercher...">
                            <!-- Options avec cases à cocher -->
                            $inputs
                        </div>
                    </div>
                    <div class="text-danger mt-2" id="errorMessage" style="display: none;">
                        Vous devez sélectionner au moins un étudiant.
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
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
            $area = '<label class="text-succes">Ce champ a déjà été rendu</label>';
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

        $fichier = urlencode($contenue);

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <span>$nomRessource</span>
                <div class="ms-auto d-flex gap-2">
                    <form method="POST" action="index.php?module=sae&action=supprimerRessource&id=$idSAE&idRessource=$idRessource" class="m-0">
                        <button type="submit" class="btn btn-secondary btn-sm">Supprimer</button>
                    </form>
                    <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$fichier" class="text-decoration-none text-primary align-self-center">Télécharger</a>
                </div>
            </div>
            HTML;
        }

        return <<<HTML
    <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
        <span>$nomRessource</span> 
        <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$fichier" class="ms-auto text-decoration-none text-primary">Télécharger</a>
    </div>
    HTML;
    }

    function lineGroupes($groupe, $etudiants, $idSAE) {
        if ($groupe == "default") {
            return <<<HTML
            <div class="align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    {$this->initCreerGroupe($etudiants, $idSAE)}
                </div>
        HTML;
        }

        $etudiants = '';
        $idGroupe = $groupe['idGroupe'];

        foreach($groupe['etudiants'] as $etudiant) {
            $etudiants .= '<span class="badge bg-primary me-1">' . $etudiant['nom'] . ' ' . $etudiant['prenom'] . '</span>';
        }

        echo <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <span>{$groupe['nomGroupe']}</span>
                <div class="ms-auto d-flex gap-2">
                    <form method="POST" action="index.php?module=sae&action=gererGroupe&id=$idSAE&idproposition=$idGroupe" style="margin: 0;">
                        $etudiants
HTML;
                        for($i = 0 ; $i < count($groupe['etudiants']) ; $i++) {
                            $idEtudiant = $groupe['etudiants'][$i]['idEleve'];
                            echo <<<HTML
                                <input type="hidden" id="etudiant$i" name="etudiant$i" value="$idEtudiant">
HTML;
                        }
        echo <<<HTML
                        <button type="submit" name="Accepter" class="btn btn-success btn-sm">Accepter</button>
                        <button type="submit" name="Refuser" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </div>
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
            $depotousupression = '<span class="text-primary text-danger text-decoration-none cursor-pointer supressRenduButton-' . $id . '">Supprimer le rendu déposer</span>';
            $voirRendu = <<<HTML
                <form method="POST" action="index.php?module=sae&action=deposerFichierRendu&id=$id" style="display: inline;">
                    <input type="hidden" name="file">
                    <button type="submit" class="btn btn-link text-primary text-success text-decoration-none p-0 m-0" style="border: none; background: none;">
                        Voir le rendu
                    </button>
                </form>
HTML;
        } else {

            $color = "danger";
            $phrase = "A déposer avant le : ";
            $depotousupression = '<div class="text-primary text-decoration-none cursor-pointer fw-bold rendudrop-' . $id . '">Déposer le rendu</div>';
        }

        if (!isset($voirRendu)) {
            $voirRendu = "";
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
                        $voirRendu
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

        if ($supportsDeposer) {
            $depotousupression = '<span class="text-primary text-danger text-decoration-none cursor-pointer supressSupportButton-' . $idSoutenance . '">Supprimer le support déposé</span>';
            $voirSupport = <<<HTML
                <form method="POST" action="index.php?module=sae&action=deposerFichierSupport&id=$idSoutenance" style="display: inline;">
                    <input type="hidden" name="file">
                    <button type="submit" class="btn btn-link text-primary text-success text-decoration-none p-0 m-0" style="border: none; background: none;">
                        Voir le support
                    </button>
                </form>
HTML;
}        else
            $depotousupression = '<span class="text-primary text-decoration-none cursor-pointer fw-bold rendusoutenance-' . $idSoutenance . '">Déposer un support</span>';


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
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$titre</p>
                </div>
                <div class="text-end">
                <p class="text-muted mb-0">Votre date de passage : $dateSoutenance</p>
                <p class="text-muted mb-1">Salle : $salle</p>
                    <div class="d-flex flex-column">
                    $depotousupression
HTML .
(  !empty($voirSupport) ? $voirSupport : '') . 
            <<<HTML
                        
                    </div>
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

        if ($groupe) {
            foreach ($groupe as $membre) {
                $nom = htmlspecialchars($membre['nom']);
                $prenom = htmlspecialchars($membre['prenom']);
                echo $this->linePersonne($prenom, $nom);
            }
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
                    Responsable(s) et intervenant(e)
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

            <!-- MESSA E -->
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
                $nom = htmlspecialchars($note['nom']) ?? "";
                $noteAttribuee = isset($note['note']);
                $noteValue = $noteAttribuee ? htmlspecialchars($note['note']) : "~";
                $coeff = htmlspecialchars($note['coef']) ?? "";

                if($noteAttribuee){
                    $totalRendus += $noteValue * $coeff;
                    $totalCoeffRendus += $coeff;
                }

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
                $noteAttribuee = isset($note['note']);

                $nom = htmlspecialchars($note['titre']);
                $noteValue = $noteAttribuee ? htmlspecialchars($note['note']) : "~";
                $coeff = htmlspecialchars($note['coeff']);

                if($noteAttribuee){
                    $totalSoutenance += $noteValue * $coeff;
                    $totalCoeffSoutenance += $coeff;
                }

                echo $this->lineNoteRendus($nom, $noteValue, $coeff);
            }
        } else {
            echo $this->lineNoteRendus("default", "default", "default");
        }

        $totalNotes = $totalRendus + $totalSoutenance;
        $totalCoeff = $totalCoeffRendus + $totalCoeffSoutenance;

        $moyenne = ($totalCoeff > 0) ? (round($totalNotes / $totalCoeff, 1)) : "~";

        echo $this->lineMoyenne($moyenne);

        echo <<<HTML
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    function initPageListeRenduGroupe($sae, $listeRendus){
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">$nom</h1>
            <div class="card shadow bg-white rounded p-4 min-h75">
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Liste des rendu(s) des groupe(s) de la SAE
                    </h3>
                    <div>
HTML;
                        $renduId = $listeRendus[0]['idRendu'];
                        echo "rendu ".$renduId;
                        foreach($listeRendus as $rendu){
                            if($rendu['idRendu'] != $renduId){
                                echo "rendu".$rendu['idRendu'];
                            }
                            echo $this->lineRenduTelechargeableGroupe($rendu['nom'], $rendu['fichier'], $sae[0]['idSAE']);
                        }
                        echo <<<HTML
                    </div>
HTML;

        return <<<HTML
            
        HTML;
    }

    function lineRenduTelechargeableGroupe($nomGroupe, $nomFichier, $idSAE){
        $nomFichierSplit = explode('-', $nomFichier);
        array_shift($nomFichierSplit);
        $nomFichierAffichage = implode('-', $nomFichierSplit);

        return <<<HTML
        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold">Rendu du groupe : $nomGroupe</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$nomFichier"  
               class="resource-item d-flex align-items-center p-2 border-bottom cursor-pointer text-decoration-none text-dark btn-download" 
               data-name="$nomFichier" 
               data-sae="$idSAE">
                <div class="d-flex flex-column w-100">
                    <p class="mb-0 fw-bold text-primary resource-name">Télécharger</p>
                    <p class="mb-0 text-muted">Contenu : $nomFichierAffichage</p>
                </div>
                </a>
            </div>
        </div>
        HTML;
    }

    function initPageListeSoutenance($sae, $soutenances, $idSae)
    {
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">$nom</h1>
            <div class="card shadow bg-white rounded p-4 min-h75">
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Soutenance(s) de la SAE que vous jugé
                    </h3>
                    <div>
        HTML;


        if (!empty($soutenances)) {
            foreach ($soutenances as $soutenance) {
                $id = htmlspecialchars($soutenance['idSoutenance']);
                $titre = htmlspecialchars($soutenance['titre']);

                echo $this->initLineSoutenance($titre, $id, $idSae);
            }
        } else {
            echo "Vous n'êtes jury d'aucunes soutenances de cette SAE";
        }

    }
    function initLineSoutenance($titre, $idsoutenance, $idSae)
    {
        return <<<HTML
        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$titre</p>
                </div>
                <div class="d-flex align-items-center">
                    <form action="index.php?module=sae&action=calendrierPassageSoutenance&id=$idSae" method="POST" id="soutenanceCalendrier">
                        <input type="hidden" name="idSoutenance" id="idSoutenance" value="$idsoutenance"/>
                        <button type="submit" class="btn btn-primary">Définir le calendrier des passages</button>
                    </form>
                </div>
            </div>
        HTML;
    }

    function initPageSoutenance($titre, $idsoutenance, $idSae, $listeGroupe, $temps){
        echo <<<HTML
                <div class="container mt-5">
                <h1 class="fw-bold">$titre</h1>
                <div class="card shadow bg-white rounded p-4 min-h75">
                    <div class="mb-5">
                        <div class="alert alert-info mt-3">
                            <p>
                                Bienvenue sur l'outil de gestion des horaires de passage. Voici comment l'utiliser :
                            </p>
                            <ul>
                                <li>Utilisez le sélecteur de date et configurez les heures de début, de fin, ainsi que la durée des plages horaires (vous pourez en créer plusieurs).</li>
                                <li>Glissez et déposez les groupes de la section "Groupe sans horaire de passage" vers les plages horaires générées dans la section de planning.</li>
                                <li>Lorsque tout est prêt, cliquez sur le bouton <strong>"Valider"</strong> pour enregistrer le planning.</li>
                            </ul>
                        </div>
                        <div class="container mt-5 d-flex flex-column" style="height: 100vh;">
                            <div class="row flex-fill">
                                <div class="col-md-3">
                                    <h4>Groupe sans horaire de passage</h4>
                                    <div id="no-groups-message" class="d-none text-center text-muted">
                                            <p>Aucuns groupes ne sont à placer dans le planning</p>
                                        </div>
                                    <div id="blocks" class="blocks overflow-auto border p-2 bg-light" style="max-height: 600px;">
                                        
        HTML;
                                        foreach($listeGroupe as $groupe){
                                            $nomGroupe = $groupe['nom'];
                                            $idGroupe = $groupe['idgroupe'];
                                            $passage = $groupe['passage'];
                                            if($passage==null){
                                                echo <<<HTML
                                                    <div class="p-3 mb-2 bg-light border rounded block" draggable="true" data-id=$idGroupe>
                                                        <input type="hidden" id="idgroupesoutenance" name="idgroupesoutenance" class="form-control block-input">$nomGroupe</input>
                                                    </div>
        HTML;
                                            }
                                            else{
                                                echo <<<HTML
                                                    <div type="hidden" class="groupeAvecPassage:$idGroupe:$nomGroupe:$passage"></div>
        HTML;
                                            }
                                        }
                                        echo <<<HTML
                                    </div>

                                    <!-- Nouvelle zone pour les groupes avec horaire -->
                                    <h4 class="mt-4">Groupe avec horaire de passage</h4>
                                    <div class="overflow-auto border p-2 bg-light" style="max-height: 400px;">
                                        <!-- Affichage des groupes avec passage -->
        HTML;
                                    foreach ($listeGroupe as $groupe) {
                                        $nomGroupe = $groupe['nom'];
                                        $idGroupe = $groupe['idgroupe'];
                                        $passage = $groupe['passage'];
                                        if ($passage != null) {
                                            echo <<<HTML
                                                <div class="p-3 mb-2 bg-light border rounded" >
                                                    <span><strong>$nomGroupe</strong></span><br>
                                                    <span>Passage : $passage</span>
                                                </div>
        HTML;
                                        }
                                    }
                                    echo <<<HTML
                                    </div>
                                </div>
            
                                <!-- Main Content: Planning -->
                                <div class="col-md-9 d-flex flex-column">
                                    <div class="mb-3">
                                        <label for="date-picker" class="form-label">Sélectionnez une date :</label>
                                        <input type="date" id="date-picker" class="form-control" />
                                    </div>
            
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 id="current-date">Date : <span></span></h4>
                                    </div>
            
                                    <div class="mb-3">
                                        <label for="start-time" class="form-label">Heure de début :</label>
                                        <input type="time" id="start-time" class="form-control" value="09:00">
                                    
                                        <label for="end-time" class="form-label mt-3">Heure de fin :</label>
                                        <input type="time" id="end-time" class="form-control" value="18:00">
                                    
                                    
                                        <label for="slot-duration" class="form-label mt-3">Durée des plages (minutes) :</label>
                                        <input type="number" id="slot-duration" class="form-control" value=$temps min="1">
                                    
                                        <p class="mt-2 text-muted">Pour changer la durée des plages, valider d'abord une première fois le changement en appuyant sur valider, puis re remplissez les plages (attention, tout les passages de la soutenance seront suprimmés).</p>
                                        
                                        <button id="generate-slots" class="btn btn-success mt-3">Générer les plages horaires</button>
                                    </div>
                                    <div id="time-slots" class="overflow-auto" style="max-height: 400px; width: 100%;"></div>
                                    
                                    <button id="validate" class="btn btn-success mt-2">Valider</button>
                                    <form id="schedule-form" method="post" action="index.php?module=sae&action=placerPassageSoutenance&id=$idSae&idsoutenance=$idsoutenance" style="display: none;">
                                        <div id="schedule-data"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="js/Sae/pageSoutenanceCalendrier/pageSoutenanceCalendrier.js"></script>
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
                    <h5 class="modal-title fw-bolder text-center w-100">Déposer une ressource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?module=sae&action=depotRessource&id=$idSae" method="POST" id="ressourceUploadForm" enctype="multipart/form-data">
                    <div class="modal-body d-flex flex-column text-center">
                        <!-- Champ pour le nom de la ressource -->
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="nomRessource" id="nomRessource" placeholder="Nom de la ressource" required>
                        </div>

                        <!-- Section pour télécharger un fichier -->
                        <div class="card border rounded-3 mb-3">
                            <div class="card-body d-flex flex-column align-items-center">
                                <svg class="icon" width="100" height="100" style="fill: #0AF;">
                                    <use xlink:href="#upload-icon"></use>
                                </svg>
                                <p>Déposer ou glisser un fichier ici</p>
                                <p class="fs-10 fw-light">Taille max : 20 Mo</p>
                                <input type="file" class="form-control-file" id="fileInputRessource" name="fileInputRessource" required style="display: none;">
                                <button type="button" class="btn btn-light w-50" id="selectFileButtonRessource">Sélectionner fichier</button>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
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

        $idsRessourcesAssociees = array_map(function ($r) {
            return $r['idRessource'];
        }, $ressourcesAssociees);

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

    function initCreerGroupe($etudiants, $idSae) {
        $options = '';
        foreach ($etudiants as $etudiant) {
            $options .= '<option value="' . htmlspecialchars($etudiant['idEleve']) . '">' . htmlspecialchars($etudiant['prenom']) . htmlspecialchars($etudiant['nom']) . '</option>';
        }

        return <<<HTML
            <h1 class="my-4">Sélectionnez les élèves</h1>
            <div class="align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <form action="index.php?module=sae&action=creation_groupe&id=$idSae" method="POST">
                    <div id="etudiants-container">
                        <div class="etudiant-container mb-3 w-25">
                            <input type="TEXT" name="nomGroupe" class="form-control mb-3 w-75" placeholder="Entrez le nom du groupe" required>
                            <select name="etudiants[]" class="form-select" id="addEtudiant" required>
                                <option value="" disable>-- Sélectionnez un élève --</option>
                                $options
                            </select>
                        </div>
                    </div>
                    <button type="button" id="addEtudiantField" class="btn btn-secondary">Ajouter un élève</button>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary ms-auto">Envoyer</button>
                    </div>
                </form>
            </div>
        HTML;
    }

    // Ressources


    function initRessources($ressources, $myRessources)
    {
        $myRessourcesIds = array_column($myRessources, 'idRessource');
        $isProf = isset($_SESSION['estProfUtilisateur']) && $_SESSION['estProfUtilisateur'];

        echo <<<HTML
    <div class="container mt-5">
        <h1 class="fw-bold">LISTE DES RESSOURCES</h1>
        <div class="card shadow bg-white rounded min-h75 p-3">
            <div class="d-flex align-items-center p-4 mx-5">
                <div class="me-3">
                    <svg width="35" height="35">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                </div>
                <h3 class="fw-bold">Liste des ressources</h3>
            </div>
            <div class="d-flex mb-4">
                <input type="text" id="search-bar" class="form-control w-50" placeholder="Rechercher une ressource">
HTML;

        // Le bouton "Afficher mes SAE" n'est affiché que pour les professeurs
        if ($isProf) {
            echo <<<HTML
                <button id="filter-sae-button" class="btn btn-secondary ms-3">Afficher mes SAE</button>
HTML;
        }

        echo <<<HTML
                <button id="sort-button" class="btn btn-primary ms-3">Trier A-Z</button>
            </div>
            <div id="ressources-list" class="ressources-list">
HTML;

        foreach ($ressources as $ressource) {
            $nomRessource = htmlspecialchars($ressource['nom']);
            $idSAE = htmlspecialchars($ressource['idRessource']);
            $contenue = htmlspecialchars($ressource['contenu']);
            $idRessource = htmlspecialchars($ressource['idRessource']);

            $isMySae = in_array($idRessource, $myRessourcesIds) ? "true" : "false";

            $hiddenClass = (!$isProf && $isMySae === "false") ? "d-none" : "";

            echo <<<HTML
        <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$contenue" 
           class="resource-item d-flex align-items-center p-2 border-bottom cursor-pointer text-decoration-none text-dark $hiddenClass" 
           data-name="$nomRessource" 
           data-sae="$idSAE" 
           data-my-sae="$isMySae">
            <div class="flex-grow-1">
                <h5 class="mb-0 fw-bold resource-name">$nomRessource</h5>
                <p class="mb-0 text-muted">Contenu: $contenue</p>
            </div>
HTML;

            if ($isProf) {
                echo <<<HTML
            <div class="ms-auto">
                <form action="index.php?module=sae&action=delRessource&idRessource=$idRessource" method="POST">
                    <input type="hidden" name="id" value="$idSAE">
                    <button type="submit" class="btn btn-danger ms-3">Supprimer</button>
                </form>
            </div>
HTML;
            }

            echo <<<HTML
        </a>
HTML;
        }

        echo <<<HTML
            </div>
        </div>
    </div>
    <script>
        isProf = {$_SESSION['estProfUtilisateur']};
    </script>
    <script src="js/ressource.js"></script>
HTML;
    }
}
