<?php

class RendusView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initRendusPage($rendus, $notes)
    {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un prof
            echo <<<HTML
                <script src="js/renduview.js"></script>
                <style>
    /* Par défaut, le tableau est visible, si tu veux qu'il soit caché au départ, tu peux ajouter "display: none;" */
.table-wrapper {
    display: block; /* Afficher par défaut */
    transition: max-height 0.3s ease-out;
}

.table-wrapper.collapsed {
    display: none;
}

/* Animation pour une transition plus douce */
.table-wrapper table {
    max-height: 1000px;
    transition: max-height 0.3s ease-out;
}

</style>


            <div class="container mt-5">
                <h1 class="fw-bold">LISTE DES RENDU(S)</h1>
                <div class="card shadow bg-white rounded min-h75">
                    <div class="d-flex align-items-center p-5 mx-5">
                        <div class="me-3">
                            <svg width="35" height="35">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                        </div>
                        <h3 class="fw-bold">Tous les rendus auxquels vous êtes responsable, co-responsable ou intervenant</h3>
                    </div>
                    <div class="rendu-list">
HTML;
            if(empty($rendus)){
                echo <<<HTML
                    <h5 class="p-5">Vous êtes associés à aucun rendus.</h5>

            HTML;
                }
            foreach ($rendus as $rendu) {
                $renduNom = $rendu['Rendu_nom'];
                $saeNom = $rendu['SAE_nom'];
                $dateLimite = $rendu['dateLimite'];
                $idSAE = $rendu['idSAE'];
                $idRendu = $rendu['idRendu'];
                
                // Filtrer les notes liées au rendu actuel
                $notesForRendu = array_filter($notes, function ($note) use ($rendu) {
                    return $note['idRendu'] === $rendu['idRendu'];
                });
                // var_dump($notes);lineRendusProf
                echo $this->lineRendusProf($renduNom, $saeNom, $dateLimite, $idSAE, $notesForRendu,$idRendu);
            }

            echo <<<HTML
                    </div>
                </div>
            </div>
HTML;
        } else { // Est un étudiant
            echo <<<HTML
            <div class="container mt-5">
                <h1 class="fw-bold">LISTE DES RENDU(S)</h1>
                <div class="card shadow bg-white rounded min-h75">
                    <div class="d-flex align-items-center p-5 mx-5">
                        <div class="me-3">
                            <svg width="35" height="35">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                        </div>
                        <h3 class="fw-bold">Liste des différents rendus des SAÉs auxquels vous êtes inscrit(e) :</h3>
                    </div>
                    <div class="rendu-list">
HTML;

            foreach ($rendus as $rendu) {

                $renduNom = $rendu['Rendu_nom'];
                $saeNom = $rendu['SAE_nom'];
                $dateLimite = $rendu['dateLimite'];
                $idSAE = $rendu['idSAE'];
                echo $this->lineRendus($renduNom, $saeNom, $dateLimite, $idSAE);
            }

            echo <<<HTML
                    </div>
                </div>
            </div>
HTML;
        }
    }

    function lineRendus($renduNom, $saeNom, $dateLimite, $idSAE)
    {
        return <<<HTML
        <div class="px-5 mx-5 my-4">
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100">
                <div class="align-items-center">
                    <span class="fw-bold mx-1 d-flex">$renduNom</span>
                    <span class="fst-italic mx-1 d-flex">$saeNom</span>
                </div>
                <div class="text-end">
                    <p class="text-danger mb-0">A déposer avant le : $dateLimite</p>
                    <a href="index.php?module=sae&action=details&id=$idSAE" class="text-primary text-decoration-none">Accéder à la SAE du rendu</a>
                </div>
            </div>
        </div>
HTML;
    }

    function lineRendusProf($renduNom, $saeNom, $dateLimite, $idSAE, $notes, $idRendu) {
        $notesTable = '';
        $uniqueNotes = [];
    
        // Filtrer les notes uniques
        foreach ($notes as $note) {
            $uniqueKey = $note['idEval'] . '_' . $note['idRendu'];
            if (!isset($uniqueNotes[$uniqueKey])) {
                $uniqueNotes[$uniqueKey] = $note;
            }
        }
    
        // Générer les lignes du tableau
        foreach ($uniqueNotes as $note) {
            $noteNom = $note['Eval_nom'] ? htmlspecialchars($note['Eval_nom'], ENT_QUOTES, 'UTF-8') : "";
            $noteId = $note['idEval'] ? $note['idEval'] : "";
            $coef = $note['Eval_coef'] ? htmlspecialchars($note['Eval_coef'], ENT_QUOTES, 'UTF-8') : "";
            $canEvaluate = $note['PeutEvaluer'] 
                ? '<a href="index.php?module=rendus&action=evaluer&eval=' . $noteId . '" class="btn btn-primary btn-sm">Évaluer</a>' 
                : 'Pas le droit';
    
            if ($noteId !== "") {
                $notesTable .= <<<HTML
                <tr>
                    <form method="POST" action="index.php?module=rendus&action=homeMaj">
                        <input type="hidden" name="idEval" value="$noteId">
                        <td>
                            <input class="input-group form-control" type="text" name="noteNom" value="$noteNom">
                        </td>
                        <td>$canEvaluate</td>
                        <td class="d-flex">
                            <input type="number" name="coef" class="form-control form-control-sm w-auto" value="$coef" placeholder="Coef">
                            <button type="submit" class="btn btn-primary btn-sm btn-success">Valider</button>
                        </td>
                    </form>
                </tr>
                HTML;
            }
        }
    
        // Contenu du tableau ou message si aucune note
        $notesSection = $notesTable 
            ? <<<HTML
            <tbody id="table-body-$idRendu">
                $notesTable
            </tbody>
            HTML
            : '<p class="text-muted mt-3">Aucune note disponible pour ce rendu.</p>';
    
        // Vue complète avec la possibilité de plier/déplier
        return <<<HTML
        <div class="px-5 mx-5 my-4">
            <!-- En-tête du rendu avec fonction de pliage et le chevron -->
            <div 
                class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100" 
                onclick="toggleTableBody('$idRendu')" 
                style="cursor: pointer;"
            >                
            
            <div class="align-items-center d-flex">
                    <i id="chevron-$idRendu" class="fas fa-chevron-down"></i>
                    <div class="ms-2">
                        <span class="fw-bold mx-1 d-flex">$renduNom</span>
                        <span class="fst-italic mx-1 d-flex">$saeNom</span>
                    </div>
                </div>
                <div class="text-end">
                    <p class="text-danger mb-0">A déposer avant le : $dateLimite</p>
                    <a href="index.php?module=sae&action=details&id=$idSAE" class="text-primary text-decoration-none">Accéder à la SAE du rendu</a>
                </div>
                <!-- Chevron pour indiquer l'état du tableau -->
            </div>
    
            <!-- Tableau complet, y compris l'en-tête et les notes -->
            <div class="mt-3 table-wrapper" id="table-wrapper-$idRendu">
                <table class="table table-bordered mt-3">
                    <!-- En-tête du tableau -->
                    <thead>
                        <tr>
                            <th>Nom de la note</th>
                            <th>Action</th>
                            <th>Coefficient</th>
                        </tr>
                    </thead>
                    <!-- Corps du tableau avec les notes -->
                    $notesSection
                </table>
                <!-- Formulaire pour ajouter une note -->
                <form method="POST" action="index.php?module=rendus&action=AjouterUneNote" class="mt-3">
                    <input type="hidden" name="idRendu" value="$idRendu">
                    <button type="submit" class="btn btn-primary btn-sm">Ajouter une note</button>
                </form>
            </div>
    
        </div>
    HTML;
    }
    
    
    
    
    public function initEvaluerPage($rendus, $notes, $infoTitre, $notesDesElvesParGroupe, $tousLesGroupes, $tousLesElevesSansGroupe) {
        if ($_SESSION["estProfUtilisateur"] == 1) { // Est un prof
            echo <<<HTML
            <div class="container mt-5">
                <h1 class="fw-bold">ATTRIBUTION DES NOTES</h1>
                <div class="card shadow bg-white rounded min-h75">
                    <div class="d-flex align-items-center p-5 mx-5">
                        <div class="me-3">
                            <svg width="35" height="35">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                        </div>
                        <h3 class="fw-bold">SAE : {$infoTitre['SAE_nom']} <br> Rendu : {$infoTitre['Rendu_nom']} <br> Évaluation : {$infoTitre['Eval_nom']}</h3>
                    </div>
                    <div class="rendu-list">
                        <form method="POST" action="index.php?module=rendus&action=maj&eval=2">
        HTML;
        
                // Organisation des données
                $groupedNotes = [];
                foreach ($notesDesElvesParGroupe as $note) {
                    $groupedNotes[$note['idEleve']] = $note;
                }
                // Afficher les groupes et leurs élèves
                foreach ($tousLesGroupes as $groupeId => $eleves) {
                    $groupeNom = htmlspecialchars($eleves['nom'], ENT_QUOTES, 'UTF-8');
                    echo <<<HTML
                    <div class="group-section mt-4">
                        <table class="table table-bordered mt-3 ">
                            <thead class="table-primary">
                                <tr class="table-primary">
                                    <h4 class="fw-bold text-primary">$groupeNom</h4>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
        HTML;
        
                    foreach ($eleves['etudiants'] as $eleve) {
                        // var_dump($groupedNotes);
                        $idEleve = $eleve['idPersonne'];
                        $eleveNom = htmlspecialchars($eleve['Eleve_nom'], ENT_QUOTES, 'UTF-8');
                        $elevePrenom = htmlspecialchars($eleve['Eleve_prenom'], ENT_QUOTES, 'UTF-8');
                        $noteValeur = isset($groupedNotes[$idEleve]['Note_valeur']) && $groupedNotes[$idEleve]['Note_valeur'] !== null 
                        ? htmlspecialchars($groupedNotes[$idEleve]['Note_valeur'], ENT_QUOTES, 'UTF-8') 
                        : '';                    echo <<<HTML
                                <tr>
                                    <td>$eleveNom</td>
                                    <td>$elevePrenom</td>
                                    <td><input type="number" name="note_$idEleve" class="form-control" value="$noteValeur" /></td>
                                </tr>
        HTML;
                    }
        
                    echo <<<HTML
                            </tbody>
                        </table>
                    </div>
        HTML;
                }

                if($tousLesElevesSansGroupe){ //ELEves sans groupes

                    echo '<h3 class="fw-bold mt-5">Élèves sans groupe</h3>';
                    echo <<<HTML
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
            HTML;
            
                    foreach ($tousLesElevesSansGroupe as $eleve) {
                        $idEleve = $eleve['idPersonne'];
                        $eleveNom = htmlspecialchars($eleve['nom'], ENT_QUOTES, 'UTF-8');
                        $elevePrenom = htmlspecialchars($eleve['prenom'], ENT_QUOTES, 'UTF-8');
                        $noteValeur = isset($groupedNotes[$idEleve]) ? htmlspecialchars($groupedNotes[$idEleve]['Note_valeur'], ENT_QUOTES, 'UTF-8') : '';
                        echo <<<HTML
                            <tr>
                                <td>$eleveNom</td>
                                <td>$elevePrenom</td>
                                <td><input type="float" name="note_$idEleve" class="form-control" value="$noteValeur" /></td>
                            </tr>
            HTML;
                    }
            }
                echo <<<HTML
                    </tbody>
                </table>
                <div class="w_100 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary btn-success m-3">Valider</button>
                </div>
            </form>
        </div>
        </div>
    HTML;
        }
    }
    

    
    
}
