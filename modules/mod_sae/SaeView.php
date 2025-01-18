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

    function initSaeDetails($profs,$sae, $champs, $repId, $ressource, $rendus, $soutenance, $rendusDeposer, $supportsDeposer)
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
        if ($_SESSION['estProfUtilisateur']) {
            echo $this->initAjoutProf($profs, $idSAE);
        }
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
                $idSoutenance = htmlspecialchars($s['idSoutenance'] ?? "default");
                $supportDeposer = $supportsDeposer[$id] ?? false;
                echo $this->lineSoutenance($titre, $dateSoutenance, $salle, $idSoutenance,$supportDeposer);
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

    function initAjoutProf($noms, $idSAE) {
        $html = '<form method="POST" action="index.php?module=sae&action=ajoutProf&id=' . $idSAE . '">
        <label for="nom">Ajouter un professeur à la SAE : </label>';
        $html .= '<select name="idPers">';
        foreach ($noms as $row) {
            $id = htmlspecialchars($row['idPersonne']);
            $nom = htmlspecialchars($row['nom']);
            $prenom = htmlspecialchars($row['prenom']);
            $html .= "<option value=\"$id\">$nom $prenom</option>";
        }
        $html .= '</select>';
        $html .= '<select name="poste">
        <option value="resp">CoResponsable</option>
        <option value="inter">Intervenant</option>
        </select>';
        $html .= '<button type="submit">Valider</button>
        </form>';
        return $html;
    }

    function lineChamp($nomChamp, $idChamps, $param, $idSAE)
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
            <form class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2" method="POST" action="index.php?module=sae&action=input_champ&id=$idSAE&idchamp=$idChamps">
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
                                    <div id="time-slots" class="overflow-auto" style="max-height: 600px; width: 100%;"></div>
                                    
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