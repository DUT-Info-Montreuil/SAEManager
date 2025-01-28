<?php
//Tout droit réservée
//All right reserved
//Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN

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
    <div class="container mt-5 h-100">
        <h1 class="fw-bold">LISTE DES SAÉ(S)</h1>
        <div class="card-general shadow bg-white rounded min-h75">
            <div class="d-flex align-items-center p-5 mx-5">
                <div class="me-3">
                    <svg width="35" height="35">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fw-bold">Liste des SAÉs auxquelles vous êtes inscrit(e) :</h3>
    HTML;
        if (empty($saes)) {
            echo <<<HTML
                        <p class="h5" >Vous êtes inscrit à aucunes Saés.</p>
    HTML;
        }
        echo <<<HTML
                </div>
            </div>
HTML;
        if (!empty($saes)) {
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

    function initSaeDetails($pasinscrits, $groupes, $infosEtudiant, $etudiants, $profs, $sae, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer, $supportsDeposer, $ressources, $listeProfSae)
    {
        $nom = htmlspecialchars($sae[0]['nomSae']);
        $dateModif = htmlspecialchars($sae[0]['dateModificationSujet']);
        $sujet = htmlspecialchars($sae[0]['sujet']);
        $idSAE = htmlspecialchars($sae[0]['idSAE']);

        echo <<<HTML
    <div class="container mt-5 colorSae=100">
        <h1 class="fw-bold">$nom</h1>
        <div class="card-general shadow bg-white rounded p-4">
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
        echo $this->popUpCreateSoutenance($idSAE, $listeProfSae);
        echo $this->popUpModifierSoutenance($idSAE);
        echo $this->popUpCreateChamp($idSAE);
        echo $this->popUpModifierSujet($idSAE, $sae);
        echo $this->popUpCreateRessource($idSAE);
        echo $this->popUpAjouterRessource($idSAE, $ressources, $ressource);
        echo <<<HTML
            </div>
HTML;
        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']) {
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
                    if ($r['misEnAvant'] != null)
                        $misEnAvant = htmlspecialchars($r['misEnAvant']);
                    else
                        $misEnAvant = 0;
                    echo $this->lineRessource($nomRessource, $idSAE, $idRessource, $contenue, $misEnAvant);
                }
            } else {
                echo $this->lineRessource("default", $sae[0]['idSAE'], "", "", "");
            }

            if ($_SESSION['estProfUtilisateur']) {

                echo <<<HTML
            <div class="d-flex gap-3 w-75">
                <button class="btn btn-primary shadow-sm px-4 mt-2 p-2 w-25"  id="btn-add-ressource">Ajouter une ressource</button>
                <button class="btn btn-primary shadow-sm px-4 mt-2 p-2 w-25"  id="create-ressource">Créer une ressource</button>
            </div>
HTML;
            }
            echo <<<HTML
                </div>
            </div>
HTML;
        }

        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']) {
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
            <div class="w-75">
                <button class="btn btn-primary shadow-sm px-4 mt-2 p-2 w-25"  id="create-rendu">Créer un rendu</button>
            </div>
HTML;
            }
            echo <<<HTML
                </div>
            </div>
HTML;
        }
        if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']) {
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
            <div class="w-75">
                <button class="btn btn-primary shadow-sm px-4 mt-2 p-2 w-25" id="create-soutenance">Créer une soutenance</button>
            </div>
HTML;
            }
        }
            if ($infosEtudiant['inGroupe'] || $_SESSION['estProfUtilisateur']) {
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
                <div class="w-75">
                     <button class="btn btn-primary shadow-sm px-4 p-2 mt-2 w-25" id="create-champ">Ajouter un champ</button>
                </div>
    HTML;
                }
                echo <<<HTML
                </div>
            </div>
    HTML;
            }

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
                        Étudiant(s)
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

        if ($_SESSION['estProfUtilisateur']) {
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
                foreach ($groupes as $groupe) {
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

        echo <<<HTML
            </div>
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
            $inputs .= '<div class="form-check student">
                            <input class="form-check-input" name="student[]" type="checkbox" value="' . $id . '" id="' . $id . '">
                            <label class="form-check-label" for="' . $id . '">' . $prenom . ' ' . $nom . '</label>
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

    function lineRessource($nomRessource, $idSAE, $idRessource, $contenue, $misEnAvant)
    {

        if ($nomRessource == "default") {
            return <<<HTML
        <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
            <span>Aucune ressource disponible</span>
        </div>
        HTML;
        }

        $svg = '';

        if ($misEnAvant)
            $svg = '<svg class="icon" width="24" height="24"><use fill="red" xlink:href="#alert-icon"></use></svg>';

        $fichier = urlencode($contenue);

        if ($_SESSION['estProfUtilisateur']) {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                $svg
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
        $svg
        <span>$nomRessource</span> 
        <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$fichier" class="ms-auto text-decoration-none text-primary">Télécharger</a>
    </div>
    HTML;
    }

    function lineGroupes($groupe, $etudiants, $idSAE)
    {
        if ($groupe == "default") {
            return <<<HTML
            <div class="align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    {$this->initCreerGroupe($etudiants,$idSAE)}
                </div>
        HTML;
        }

        $etudiants = '';
        $idGroupe = $groupe['idGroupe'];

        foreach ($groupe['etudiants'] as $etudiant) {
            $etudiants .= '<span class="badge bg-primary me-1">' . $etudiant['nom'] . ' ' . $etudiant['prenom'] . '</span>';
        }

        echo <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                <span>{$groupe['nomGroupe']}</span>
                <div class="ms-auto d-flex gap-2">
                    <form method="POST" action="index.php?module=sae&action=gererGroupe&id=$idSAE&idproposition=$idGroupe" style="margin: 0;">
                        $etudiants
HTML;
        for ($i = 0; $i < count($groupe['etudiants']); $i++) {
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

    function lineDocument($dateDepot, $idDoc, $nomAuteur, $prenomAuteur, $idSAE, $nomDoc) {
        if ($dateDepot == "default") {
            return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>Aucun document disponible</span>
                </div>
        HTML;
        }

        return <<<HTML
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$nomDoc</p>
                </div>
                <div class="text-end">
                <p class="text-none mb-0">Document déposé le : $dateDepot</p>
                <p class="text-none mb-0">Par : $nomAuteur $prenomAuteur</p>
                    <div class="d-flex flex-column">
                        <span class="text-primary text-danger text-decoration-none cursor-pointer supressDocumentButton-$idDoc">Supprimer le document déposé</span>
                        <form method="POST" action="index.php?module=sae&action=deposerFichierDocument&id=$idDoc" style="display: inline;">
                            <input type="hidden" name="file">
                            <button type="submit" class="btn btn-link text-primary text-success text-decoration-none p-0 m-0" style="border: none; background: none;">
                                Voir le document
                            </button>
                        </form>
                    </div>
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
        } else
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
            (!empty($voirSupport) ? $voirSupport : '') .
            <<<HTML
                        
                    </div>
                </div>
            </div>

        HTML;
    }

    function initCloudPage($groupeID, $groupeDocs, $idSAE) {

        echo $this->popUpDepot("Document", $idSAE);
        echo $this->popUpSupressionDepot("Document", $idSAE);
        
            // Documents
            echo <<<HTML
                <!-- Documents(s) -->
                <div class="container mt-5 h-100">
                    <h1 class="fw-bold">Cloud du groupe</h1>
                    <div class="card shadow bg-white rounded p-4 min-h75">
                        <div class="mb-5">
                            <div class="d-flex justify-content-end">
            HTML;
                            if($groupeID){
                                echo <<<HTML
                                <button class="btn mb-3 btn-primary documentdrop-$groupeID">Déposer un document</button>
                                HTML;
                            }
                                echo <<<HTML
                            </div>
                            <h3 class="fw-bold d-flex align-items-center">
                                <svg class="me-2" width="25" height="25">
                                    <use xlink:href="#arrow-icon"></use>
                                </svg>
                                Document(s)
                            </h3>
                            <div class="d-flex flex-column">
            HTML;
                    if (!empty($groupeDocs)) {
                        foreach ($groupeDocs as $d) {
                            $dateDepot = htmlspecialchars($d['dateDepot']);
                            $idDoc = htmlspecialchars($d['idDoc']);
                            $nomAuteur = htmlspecialchars($d['nom']);
                            $prenomAuteur = htmlspecialchars($d['prenom']);
                            $nomDoc = htmlspecialchars($d['Nom']);
            
                            echo $this->lineDocument($dateDepot, $idDoc, $nomAuteur, $prenomAuteur, $idSAE, $nomDoc);
                        }
                    } else {
                        echo $this->lineDocument("default", "", "", "", "", "");
                    }
                    echo <<<HTML
                            </div>
                        </div>
                    </div>
                    <script src="js/cloud.js"></script>
                </div>
    HTML;
    }

    function initCloudNoGroupePage() {

            echo <<<HTML
                <!-- Documents(s) -->
                <div class="container mt-5 h-100">
                    <h1 class="fw-bold">Cloud du groupe</h1>
                    <div class="card shadow bg-white rounded p-4 min-h75">
                        <div class="mb-5">
                            <div class="d-flex justify-content-end">
                                <button class="btn mb-3 btn-primary documentdrop-$groupeID">Déposer un document</button>
                            </div>
                            <h3 class="fw-bold d-flex align-items-center">
                                <svg class="me-2" width="25" height="25">
                                    <use xlink:href="#arrow-icon"></use>
                                </svg>
                                Document(s)
                            </h3>
                            <div class="d-flex flex-column">
            HTML;
                    if (!empty($groupeDocs)) {
                        foreach ($groupeDocs as $d) {
                            $dateDepot = htmlspecialchars($d['dateDepot']);
                            $idDoc = htmlspecialchars($d['idDoc']);
                            $nomAuteur = htmlspecialchars($d['nom']);
                            $prenomAuteur = htmlspecialchars($d['prenom']);
                            $nomDoc = htmlspecialchars($d['Nom']);
            
                            echo $this->lineDocument($dateDepot, $idDoc, $nomAuteur, $prenomAuteur, $idSAE, $nomDoc);
                        }
                    } else {
                        echo $this->lineDocument("default", "", "", "", "", "");
                    }
                    echo <<<HTML
                            </div>
                        </div>
                    </div>
                    <script src="js/cloud.js"></script>
                </div>
    HTML;
    }

    /* Groupes */

    function initGroupPage($sae, $groupe, $responsable, $members)
    {

        if (!$_SESSION['estProfUtilisateur']) {
            if ($groupe){
                $groupeName = $groupe[0]['GroupeName'] ? htmlspecialchars($groupe[0]['GroupeName']) : "";
                $groupePhoto = $groupe[0]['imageTitre'] ? htmlspecialchars($groupe[0]['imageTitre']) : "";
                $idGroupe = $groupe[0]['idGroupe'] ? htmlspecialchars($groupe[0]['idGroupe']) : "";
                echo $this->popUpModifierNomGroupe($sae[0]['idSAE'], $idGroupe);
            }
        }


        $nomSAE = htmlspecialchars($sae[0]['nomSae']);


        echo <<<HTML
    <div class="container mt-5 h-100">
        <h1 class="fw-bold">$nomSAE</h1>
        <div class="card-general shadow bg-white rounded p-4 min-h75">
            <!-- MEMBRE DU GROUPE -->
            
HTML;

        if ($_SESSION['estProfUtilisateur']) {
            echo <<<HTML
            <div class="mb-5">
                <h3 class="d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Liste des étudiants
                </h3>
                <div class="d-flex flex-wrap">
HTML;

            if ($members) {
                foreach ($members as $membre) {
                    $nom = htmlspecialchars($membre['nom']);
                    $prenom = htmlspecialchars($membre['prenom']);
                    $photo = htmlspecialchars($membre['photoDeProfil']);
                    echo $this->linePersonne($prenom, $nom, $photo);
                }
            }

            echo <<<HTML
                </div>
            </div>
HTML;
        } else {
            echo <<<HTML
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Titre et icône -->
                        <h3 class="d-flex align-items-center">
                            <svg class="me-2" width="25" height="25">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                            Membre du groupe
                        </h3>
                        <!-- Nom du groupe et image -->
                        <div class="d-flex align-items-center">
                <!-- Formulaire d'upload d'image -->
             HTML;
             if($groupe){
                
             
            echo <<<HTML
            <form id="groupImageForm" action="index.php?module=sae&action=uploadGroupImage&idGroupe=$idGroupe" method="POST" enctype="multipart/form-data">
                <label for="groupImage" style="cursor: pointer; margin-right: 10px;">
                    <img src="http://saemanager-api.atwebpages.com/api/api.php?file=$groupePhoto" 
                        alt="Logo du groupe $groupeName" 
                        class="rounded-circle" 
                        style="width: 40px; height: 40px; object-fit: cover;">
                </label>
HTML;
            if ($groupe[0]['estModifiableParEleve']) {
                echo <<<HTML
                <input type="file" id="groupImage" name="groupImage" accept="image/*" style="display: none;" onchange="document.getElementById('groupImageForm').submit();">
HTML;
            }
            echo <<<HTML
            </form>
            <!-- Nom du groupe avec bouton Modifier -->
HTML;

            if ($groupe[0]['estModifiableParEleve']) {
                echo <<<HTML
            <span class="fw-bold me-3" style="font-size: 1rem;">Groupe  : $groupeName</span>
            <a id="edit-group-name" class="text-primary text-decoration-underline cursor-pointer">Modifier</a>

HTML;
            } else {
                echo <<<HTML
            <span class="fw-bold me-3" style="font-size: 1rem;">Groupe : $groupeName</span>
HTML;
            }

            echo <<<HTML
        </div>
    </div>
    <div class="d-flex flex-wrap">
HTML;
        }


            if ($groupe) {
                foreach ($groupe as $membre) {
                    $nom = htmlspecialchars($membre['nom']);
                    $prenom = htmlspecialchars($membre['prenom']);
                    $photo = htmlspecialchars($membre['photoDeProfil']);
                    echo $this->linePersonne($prenom, $nom, $photo);
                }
            }

            echo <<<HTML
                </div>
            </div>
HTML;
        }
        echo <<<HTML
            <!-- RESPONSABLE -->
            <div class="mb-5">
                <h3 class="d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Responsable(s) et intervenant(e)(s)
                </h3>
                <div class="d-flex flex-wrap">
HTML;
        foreach ($responsable as $resp) {
            $nom = htmlspecialchars($resp['nom']);
            $prenom = htmlspecialchars($resp['prenom']);
            $photo = htmlspecialchars($resp['photoDeProfil']);
            echo $this->linePersonne($prenom, $nom, $photo);
        }
        echo <<<HTML
                </div>
            </div>

        </div>
    </div>

    <script src="js/groupe.js"></script>
    HTML;
    }

    function linePersonne($prenom, $nom, $photo)
    {
        return <<<HTML
    <div class="px-3 my-3 w-200px">
        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm">
            <div class="d-flex align-items-center">
                <img src="http://saemanager-api.atwebpages.com/api/api.php?file=$photo" alt="Photo de $prenom $nom" class="rounded-circle mx-2" style="width: 25px; height: 25px; object-fit: cover;">
                <span class="fw-bold mx-1">$prenom $nom</span>
            </div>
        </div>
    </div>
HTML;
    }

    // Notes

    function initNotePage($notes, $sae, $noteSoutenance){
        foreach ($sae as $s) {
            $nom = htmlspecialchars($s['nomSae']);
        }

        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">$nom</h1>
            <div class="card-general shadow bg-white rounded p-4 min-h75">
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

                if ($noteAttribuee) {
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

                $nom = htmlspecialchars($note['nom']);
                $noteValue = $noteAttribuee ? htmlspecialchars($note['note']) : "~";
                $coeff = htmlspecialchars($note['coef']);

                if ($noteAttribuee) {
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

    function initPageReponsesChampGroupe($sae, $listeReponsesSae){
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">$nom</h1>
            <div class="card shadow bg-white rounded p-4 min-h75">
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Liste des réponses des groupe(s) de la SAE
                    </h3>
                    <div>
HTML;
                        if($listeReponsesSae){
                            $idChamp = $listeReponsesSae[0]['idChamps'];
                            echo $this->catégorieChamp($listeReponsesSae[0]['nomchamp'], $listeReponsesSae[0]['idChamps']);
                            foreach($listeReponsesSae as $reponse){
                                if($reponse['idChamps'] != $idChamp){
                                    $idChamp = $reponse['idChamps'];
                                    echo $this->catégorieChamp($reponse['nomchamp'], $reponse['idChamps']);
                                }
                                echo $this->lineReponsesChampEleve($reponse['nom'], $reponse['prenom'], $reponse['reponse'], $sae[0]['idSAE'], $reponse['idChamps']);
                            }
                        }
                        else{
                            echo <<<HTML
                                <p class="h5 fw-bold">Aucuns élèves ont répondus aux différents champs de cette SAE</p>

HTML;
                        }
                        echo <<<HTML
                    </div>
HTML;

        echo <<<HTML
            <script src="js/reponseschamp.js"></script>
        HTML;
    }

    function initPageListeSupportGroupe($sae, $listesSupport){
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">$nom</h1>
            <div class="card shadow bg-white rounded p-4 min-h75">
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Liste des support(s) des groupe(s) de la SAE
                    </h3>
                    <div>
HTML;
                        if($listesSupport){
                            $soutenanceID = $listesSupport[0]['idSoutenance'];
                            echo $this->catégorieSupport($listesSupport[0]['nomSoutenance'], $listesSupport[0]['idSoutenance']);
                            foreach($listesSupport as $soutenance){
                                if($soutenance['idSoutenance'] != $soutenanceID){
                                    $soutenanceID = $soutenance['idSoutenance'];
                                    echo $this->catégorieSupport($soutenance['nomSoutenance'], $soutenance['idSoutenance']);
                                }
                                echo $this->lineSupportTelechargeableGroupe($soutenance['nom'], $soutenance['fichier'], $sae[0]['idSAE'], $soutenance['idSoutenance']);
                            }
                        }
                        else{
                            echo <<<HTML
                                <p class="h5 fw-bold">Aucuns support n'ont été déposés pour les soutenances de cette SAE</p>

HTML;
                        }
                        echo <<<HTML
                    </div>
HTML;

        echo <<<HTML
            <script src="js/supportdownload.js"></script>
        HTML;
    }

    function catégorieSupport($nomSoutenance, $idSoutenance){
        return <<<HTML
        <div class="d-flex justify-content-between p-3 border bg-light rounded-3 shadow-sm mb-2 w-100"
        style="cursor: pointer;">
            <i class="chevronSupport-$idSoutenance fas fa-chevron-up"></i>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">$nomSoutenance</p>
            </div>   
        </div>
HTML;

    }

    function catégorieChamp($nomChamp, $idChamp){
        return <<<HTML
        <div class="d-flex justify-content-between p-3 border bg-light rounded-3 shadow-sm mb-2 w-100"
        style="cursor: pointer;">
            <i class="chevronChamp-$idChamp fas fa-chevron-up"></i>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">$nomChamp</p>
            </div>   
        </div>
HTML;
    }

    function lineReponsesChampEleve($nomEleve, $prenomEleve, $reponse, $idSAE, $idChamp){
        return <<<HTML
        <div class="lineReponsesChamp-$idChamp d-flex d-none justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2 w-100">
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">Élève : $nomEleve $prenomEleve</p>
            </div>
            <div class="d-flex flex-column w-75">
                <p class="mb-0 text-muted fw-bold">Réponse : </p>$reponse
            </div>
        </div>
        HTML;
    }

    function lineSupportTelechargeableGroupe($nomGroupe, $nomFichier, $idSAE, $idSoutenance){
        $nomFichierSplit = explode('-', $nomFichier);
        array_shift($nomFichierSplit);
        $nomFichierAffichage = implode('-', $nomFichierSplit);

        return <<<HTML
        <div class="lineSupportTelechargeable-$idSoutenance d-flex d-none justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2 w-100">
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">Support du groupe : $nomGroupe</p>
            </div>
            <div class="d-flex flex-column w-25">
                <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$nomFichier"  
                class="d-flex align-items-center cursor-pointer text-decoration-none text-dark" 
                data-name="$nomFichier" 
                data-sae="$idSAE">
                        <p class="mb-0 fw-bold text-primary resource-name">Télécharger</p>
                </a>
                <p class="mb-0 text-muted">Contenu : $nomFichierAffichage</p>
            </div>
        </div>
        HTML;
    }

    function initPageListeRenduGroupe($sae, $listeRendus){
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5 h-100">
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
                        if($listeRendus){
                            $renduId = $listeRendus[0]['idRendu'];
                            echo $this->catégorieRendu($listeRendus[0]['nomRendu'], $listeRendus[0]['idRendu']);
                            foreach($listeRendus as $rendu){
                                if($rendu['idRendu'] != $renduId){
                                    $renduId = $rendu['idRendu'];
                                    echo $this->catégorieRendu($rendu['nomRendu'], $rendu['idRendu']);
                                }
                                echo $this->lineRenduTelechargeableGroupe($rendu['nom'], $rendu['fichier'], $sae[0]['idSAE'], $rendu['idRendu']);
                            }
                        }
                        else{
                            echo <<<HTML
                                <p class="h5 fw-bold">Aucuns fichier n'ont été déposés pour les rendus de cette SAE</p>

HTML;
                        }
                        echo <<<HTML
                    </div>
HTML;

        echo <<<HTML
            <script src="js/rendudownload.js"></script>
        HTML;
    }

    function catégorieRendu($nomRendu, $idRendu){
        return <<<HTML
        <div class="d-flex justify-content-between p-3 border bg-light rounded-3 shadow-sm mb-2 w-100"
        style="cursor: pointer;">
            <i class="chevronRendu-$idRendu fas fa-chevron-up"></i>
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">$nomRendu</p>
            </div>   
        </div>
HTML;
    }

    function lineRenduTelechargeableGroupe($nomGroupe, $nomFichier, $idSAE, $idRendu){
        $nomFichierSplit = explode('-', $nomFichier);
        array_shift($nomFichierSplit);
        $nomFichierAffichage = implode('-', $nomFichierSplit);

        return <<<HTML
        <div class="lineRenduTelechargeable-$idRendu d-flex d-none justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2 w-100">
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold w-10">Rendu du groupe : $nomGroupe</p>
            </div>
            <div class="d-flex flex-column w-25">
                <a href="http://saemanager-api.atwebpages.com/api/api.php?file=$nomFichier"  
                class="d-flex align-items-center cursor-pointer text-decoration-none text-dark" 
                data-name="$nomFichier" 
                data-sae="$idSAE">
                        <p class="mb-0 fw-bold text-primary resource-name">Télécharger</p>
                </a>
                <p class="mb-0 text-muted">Contenu : $nomFichierAffichage</p>
            </div>
        </div>
        HTML;
    }

    function initPageListeSoutenance($sae, $soutenances, $idSae)
    {
        $nom = htmlspecialchars($sae[0]['nomSae']);
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">$nom</h1>
            <div class="card-general shadow bg-white rounded p-4 min-h75">
                <div class="mb-5">
                    <h3 class="d-flex align-items-center">
                        <svg class="me-2" width="25" height="25">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                        Soutenance(s) de la SAE que vous jugez
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
            echo "<p class='h5 fw-bold'>Vous n'êtes jury d'aucunes soutenances de cette SAE</p>";
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

    function initPageSoutenance($titre, $idsoutenance, $idSae, $listeGroupe, $temps)
    {
        echo <<<HTML
                <div class="container mt-5">
                <h1 class="fw-bold">$titre</h1>
                <div class="card-general shadow bg-white rounded p-4 min-h75">
                    <div class="mb-5">
                        <div class="alert alert-info mt-3">
                            <p>
                                Bienvenue sur l'outil de gestion des horaires de passage. Voici comment l'utiliser :
                            </p>
                            <ul>
                                <li>Utilisez le sélecteur de date et configurez les heures de début, de fin, ainsi que la durée des plages horaires (vous pourrez en créer plusieurs).</li>
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
        foreach ($listeGroupe as $groupe) {
            $nomGroupe = $groupe['nom'];
            $idGroupe = $groupe['idgroupe'];
            $passage = $groupe['passage'];
            if ($passage == null) {
                echo <<<HTML
                                                    <div class="p-3 mb-2 bg-light border rounded block" draggable="true" data-id=$idGroupe>
                                                        <input type="hidden" id="idgroupesoutenance" name="idgroupesoutenance" class="form-control block-input">$nomGroupe</input>
                                                    </div>
        HTML;
            } else {
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
                                    
                                        <p class="mt-2 text-muted">Pour changer la durée des plages, valider d'abord une première fois le changement en appuyant sur valider, puis re remplissez les plages (attention, tous les passages de la soutenance seront suprimés).</p>
                                        
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

    function popUpDepot($typeDepot, $idSae)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalDepot$typeDepot">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Déposer un $typeDepot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButton$typeDepot"></button>
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
    function popUpModifierNomGroupe($idSae, $idGroupe)
    {
        return <<<HTML
        <div class="modal" tabindex="-1" id="modalModifierNomGroupe">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Modifier le nom du groupe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?module=sae&action=modifierNomGroupe&id=$idSae&idGroupe=$idGroupe" method="POST">
                        <div class="modal-body d-flex flex-column text-center">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" name="nomGroupe" id="nomGroupe" placeholder="Nom du groupe" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success m-3">Valider</button>
                                <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal" id="modal-groupe-cancel">Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    HTML;
    }

    function popUpSupressionDepot($typeDepot, $idSae)
    {
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonCreateRendu"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonCreateRessource"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonAjouterRessource"></button>
                </div>
                <form action="index.php?module=sae&action=ajouterRessource&id=$idSae" method="POST">
                    <div class="modal-body d-flex flex-column text-center">
                        <div class="form-group mb-3">
                            <label for="ressourceSelect">Sélectionner une ressource</label>
                            <select class="form-control" id="ressourceSelect" name="ressourceSelect" required>
                                $options
                            </select>
                            <input name="enAvant" type="checkbox">Mettre en avant
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonCreateChamp"></button>
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

    function popUpCreateSoutenance($idSae, $profsSae)
    {
        $inputs = '';
        foreach($profsSae as $prof){
            $nom = $prof['nom'];
            $prenom = $prof['prenom'];
            $inputs.='
            <div class="form-check-profs">
                <input class="form-check-input" name="profs[]" type="checkbox" value="' . $prof['idPersonne'] . '" id="' . $prof['idPersonne'] . '">
                <label class="form-check-label" for="' . $prof['idPersonne'] . '">' . $prenom . ' ' . $nom . '</label>
            </div>';
        }

        return <<<HTML
        <div class="modal" tabindex="-1" id="modalCreateSoutenance">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder text-center w-100">Créer une soutenance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonCreateSoutenance"></button>
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
                            <label class="form-label fw-bold">Ajoutez un jury à la soutenance:</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle-profs w-100" type="button" id="dropdownButton">Rechercher des profs</button>
                                <div class="dropdown-menu p-2 w-100" id="dropdownContent-profs" style="display: none;">
                                <input 
                                type="text" 
                                class="form-control mb-2" 
                                id="searchInput-profs" 
                                placeholder="Rechercher...">    
                                $inputs
                                </div>
                            </div>
                            <div class="text-danger mt-2" id="errorMessage-profs" style="display: none;">
                                Vous devez sélectionner au moins un étudiant.
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonModifierSoutenance"></button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonModifierRendu"></button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modalCancelButtonModifierSujet"></button>
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

    function initCreerGroupe($etudiants, $idSae)
    {
        $options = '';
        foreach ($etudiants as $etudiant) {
            $options .= '<option value="' . htmlspecialchars($etudiant['idEleve']) . '">' . htmlspecialchars($etudiant['prenom']) . htmlspecialchars($etudiant['nom']) . '</option>';
        }

        $edit = '';
        if ($_SESSION['estProfUtilisateur']) {
            $edit = '<div class="form-check my-3">
            <input class="form-check-input" type="checkbox" id="editableGroup" name="editableGroup">
            <label class="form-check-label" for="editableGroup">
                Permettre aux membres de modifier le nom et la photo du groupe.
            </label>
        </div>';
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
                    $edit
                    <button type="button" id="addEtudiantField" class="btn btn-secondary">Ajouter un élève</button>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary ms-auto">Créer le groupe</button>
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
    <div class="container mt-5 h-100">
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
    <script src="js/ressource.js"></script>
        isProf = {$_SESSION['estProfUtilisateur']};
    
HTML;
    }
}
