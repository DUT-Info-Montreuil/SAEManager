<?php

class RendusView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initRendusPage($rendus, $notes,$intervenants)
    {
        if ($_SESSION["estProfUtilisateur"] == 1) {
            echo <<<HTML
            <div class="container mt-5 h-100">
                <h1 class="fw-bold">ÉVALUER DES RENDU(S)</h1>
                <div class="card-general shadow bg-white rounded min-h75">
                    <div class="d-flex align-items-center p-5 mx-5">
                        <div class="me-3">
                            <svg width="35" height="35">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                        </div>
                        <h3 class="fw-bold">Tous les rendus auxquels vous êtes responsable, co-responsable ou intervenant des SAE</h3>
                    </div>
                    <div class="rendu-list">
HTML;
            if (empty($rendus)) {
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


                $notesForRendu = array_filter($notes, function ($note) use ($rendu) {
                    return $note['idRendu'] === $rendu['idRendu'];
                });
                echo $this->lineRendusProf($renduNom, $saeNom, $dateLimite, $idSAE, $notesForRendu,$idRendu,$intervenants);
            }

            echo <<<HTML
                    </div>
                </div>
            </div>
HTML;
        } else {
            echo <<<HTML
            <div class="container mt-5 h-100">
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
            $renduNom = htmlspecialchars($rendu['Rendu_nom']);
            $saeNom = htmlspecialchars($rendu['SAE_nom']);
            $dateLimite = htmlspecialchars($rendu['dateLimite']);
            $idSAE = htmlspecialchars($rendu['idSAE']);
            echo $this->lineRendus($renduNom, $saeNom, $dateLimite, $idSAE);
        }
        <<<HTML
                </div>
            </div>        
        </div>
        
HTML;
        }
    }
    function initScript(){
        echo '<script src="js/renduview.js"></script>';
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

    function lineRendusProf($renduNom, $saeNom, $dateLimite, $idSAE, $notes, $idRendu,$intervenants) {
        $notesTable = '';
        $uniqueNotes = [];

        foreach ($notes as $note) {
            $uniqueKey = $note['idEval'] . '_' . $note['idRendu'];
            if (!isset($uniqueNotes[$uniqueKey])) {
                $uniqueNotes[$uniqueKey] = $note;
            }
        }

        foreach ($uniqueNotes as $note) {
            $noteNom = $note['Eval_nom'] ? htmlspecialchars($note['Eval_nom'], ENT_QUOTES, 'UTF-8') : "";
            $noteId = $note['idEval'] ? $note['idEval'] : "";
            $coef = $note['Eval_coef'] ? htmlspecialchars($note['Eval_coef'], ENT_QUOTES, 'UTF-8') : "";
            $idDeCetteSae = $note['idSAE'] ? htmlspecialchars($note['idSAE']) : "";
            $idIntervenantEvaluateur = $note['idIntervenantEvaluateur'] ? htmlspecialchars($note['idIntervenantEvaluateur']) : "";
            $canEvaluate = $note['PeutEvaluer']
                ? '<a href="index.php?module=rendus&action=evaluer&eval=' . $noteId . '" class="btn btn-primary btn-sm">Évaluer</a>'
                : 'Vous n\'êtes pas évaluateur';

            if ($noteId !== "") {
                $notesTable .= <<<HTML
                <tr>
                    <form method="POST" action="index.php?module=rendus&action=homeMaj">
                        <input type="hidden" name="idEval" value="$noteId">
                        <td class="text-center">
                            <input class="input-group form-control" type="text" name="noteNom" value="$noteNom" placeholder="Nom de l'évaluation">
                        </td>
                        <td class="text-center">
                            <input type="number" name="coef" class="form-control form-control-sm w-auto" min="0" max="100" value="$coef" placeholder="Coef">
                        </td>
                        <td class="text-center">
                            {$this->initAjoutIntervenant($intervenants,$idDeCetteSae,$idIntervenantEvaluateur)}
                        </td>
                        <td class="align-center d-flex">
                            $canEvaluate
                            <button type="submit" class="btn btn-primary btn-sm btn-success ms-2">Valider</button>
                            <a href="index.php?module=rendus&action=supprimerEval&idEval=$noteId" type="submit" class="btn btn-outline-danger btn-sm ms-2">Supprimer</a>
                        </td>
                    </form>
                </tr>
                HTML;
            }
        }

        $notesSection = $notesTable
            ? <<<HTML
            <tbody id="table-body-$idRendu">
                <thead>
                    <tr>
                        <th>Nom de la note</th>
                        <th>Coefficient</th>
                        <th>Intervenant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                $notesTable
            </tbody>
            HTML
            : '<p class="text-muted mt-3">Aucune note disponible pour ce rendu.</p>';

        $dateToCheck = date('Y-m-d H:i:s');

        $dateTime = new DateTime($dateLimite);
        $dateTimeToCheck = new DateTime($dateToCheck);

        if ($dateTimeToCheck > $dateTime)
            $color = "danger";
        else if ($dateTimeToCheck > (clone $dateTime)->modify('-24 hours'))
            $color = "warning";
        else
            $color = "success";

        return <<<HTML
        <div class="px-5 mx-5 my-4">
            <div 
                class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100 table-header" 
                onclick="toggleTableBody('$idRendu')" 
                style="cursor: pointer;">                
            
                <div class="align-items-center d-flex">
                    <i id="chevron-$idRendu" class="fas fa-chevron-down"></i>
                    <div class="ms-2">
                        <span class="fw-bold mx-1 d-flex">$renduNom</span>
                        <span class="fst-italic mx-1 d-flex">$saeNom</span>
                    </div>
                </div>
                <div class="text-end">
                    <p class="text-$color mb-0">Le dépot se finit dans : $dateLimite</p>
                    <a href="index.php?module=sae&action=details&id=$idSAE" class="text-primary text-decoration-none">Accéder à la SAE du rendu</a>
                </div>
            </div>
    
            <div class="mt-3 table-wrapper" id="table-wrapper-$idRendu">
                <table class="table table-bordered mt-3">
                    
                    $notesSection
                </table>
                <form method="POST" action="index.php?module=rendus&action=AjouterUneNote" class="mt-3">
                    <input type="hidden" name="idRendu" value="$idRendu">
                    <button type="submit" class="btn btn-primary btn-sm">Ajouter une note</button>
                </form>
            </div>
    
        </div>
    HTML;
    }

    
    function initAjoutIntervenant($intervenants, $idEval,$idIntervenantEvaluateur) {
        $options = '';
        
        foreach ($intervenants as $intervenant) {
            $testIdEval = $intervenant['idSAE'] ? htmlspecialchars($intervenant['idSAE']) : "";
            $id = $intervenant['Intervenant_id'] ? htmlspecialchars($intervenant['Intervenant_id']) : "";
            $nom = $intervenant['Intervenant_nom'] ? htmlspecialchars($intervenant['Intervenant_nom']) : "";
            $prenom = $intervenant['Intervenant_prenom'] ? htmlspecialchars($intervenant['Intervenant_prenom']) : "";
            if ($id && $testIdEval == $idEval) {
                $options .= '<option name="id_intervenent_'.$id.'" value="' . $id . '" ';
                if ($idIntervenantEvaluateur==$id){
                    $options .= "selected";
                }  
                $options .= '>' . $prenom . ' ' . $nom . '</option>';
            }
        }
        
        return <<<HTML
        <select class="form-select" name="intervenant[]">
            <option value="" >Aucun intervenant</option>
            $options
        </select>
    HTML;
    }



    public function initEvaluerPage($rendus, $notes, $infoTitre, $notesDesElvesParGroupe, $tousLesGroupes, $tousLesElevesSansGroupe)
    {
        if ($_SESSION["estProfUtilisateur"] == 1) {
            echo <<<HTML
            <div class="container mt-5 h-100">
                <h1 class="fw-bold">ATTRIBUTION DES NOTES</h1>
                <div class="card-general shadow bg-white rounded min-h75">
                    <div class="d-flex align-items-center p-5 mx-5">
                        <div class="me-3">
                            <svg width="35" height="35">
                                <use xlink:href="#arrow-icon"></use>
                            </svg>
                        </div>
                        <h3 class="fw-bold">SAE : {$infoTitre['SAE_nom']} <br> Rendu : {$infoTitre['Rendu_nom']} <br> Évaluation : {$infoTitre['Eval_nom']}</h3>
                    </div>
                    <div class="rendu-list p-3">
                        <form method="POST" action="index.php?module=rendus&action=maj&eval=2">
            HTML;

            $groupedNotes = [];
            foreach ($notesDesElvesParGroupe as $note) {
                $groupedNotes[$note['idEleve']] = $note;
            }

            foreach ($tousLesGroupes as $groupeId => $eleves) {
                $groupeNom = htmlspecialchars($eleves['nom'], ENT_QUOTES, 'UTF-8');
                $groupeId = htmlspecialchars($groupeId, ENT_QUOTES, 'UTF-8');

                echo <<<HTML
                <div class="group-section mt-4">
                    <div class="d-flex align-items-center table-header" onclick="toggleGroup('$groupeId')">
                         <i id="chevron-$groupeId" class="fas fa-chevron-down me-2"></i> <!--chevron déroulant -->
                        <h4 class="fw-bold text mb-0">$groupeNom</h4>
                        <div class="ml-auto">
                            <label for="note">Ajouter une note globale au groupe :</label>
                            <input type="number" class="form-control" id="global-note-$groupeId" value="" min="0" max="20" onchange="updateGroupNotes('$groupeId')" onclick="preventGroupToggle(event, '$groupeId')" />
                        </div>
                    </div>
                    <!-- Tableau des élèves -->
                    <div class="group-table mt-3 collapsed" id="table-wrapper-$groupeId">
                        <table class="table table-bordered mt-3">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                HTML;

                foreach ($eleves['etudiants'] as $eleve) {
                    $eleveNom = htmlspecialchars($eleve['Eleve_nom'], ENT_QUOTES, 'UTF-8');
                    $elevePrenom = htmlspecialchars($eleve['Eleve_prenom'], ENT_QUOTES, 'UTF-8');
                    $idEleve = $eleve['idPersonne'];
                    $noteValeur = isset($groupedNotes[$idEleve]) ? $groupedNotes[$idEleve]['Note_valeur'] : '';

                    echo <<<HTML
                                <input type="hidden" name="idEval" class="form-control" value="{$infoTitre['idEval']}">
                                <tr>
                                    <td>$eleveNom</td>
                                    <td>$elevePrenom</td>
                                    <td><input type="number" name="note_idEleve_$idEleve" class="form-control" value="$noteValeur" min="0" max="20" id="note-$idEleve" /></td>
                                </tr>
                    HTML;
                }

                echo <<<HTML
                            </tbody>
                        </table>
                    </div>
                </div>
                HTML;
            }
    
            
    
            echo <<<HTML
                <div class="w_100 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary btn-success m-3">Valider</button>
                </div>
            </form>
            </div>
        </div>
        HTML;
        }
    }

    function initSupprimerEval($idEval){
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">SUPPRIMER UNE ÉVALUATION</h1>
            <div class="card shadow bg-white rounded min-h75">
                <div class="d-flex align-items-center p-5 mx-5">
                    <div class="me-3">
                        <svg width="35" height="35">
                            <use xlink:href="#arrow-icon"></use>
                        </svg>
                    </div>
                    <h3 class="fw-bold">Voulez-vous vraiment supprimer cette évaluation ?</h3>
                </div>
                <div class="rendu-list">
                    <form method="POST" action="index.php?module=rendus&action=confirmationSupprimerEval&idEval=$idEval">
                        <div class="w_100 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-danger m-3">Oui, supprimer l'évaluation et toutes les notes liés</button>
                            <a href="javascript:history.go(-1)" class="btn btn-primary btn-success m-3">Non, annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        HTML;
    }
    
    
    
    
    
    

    
    
}
