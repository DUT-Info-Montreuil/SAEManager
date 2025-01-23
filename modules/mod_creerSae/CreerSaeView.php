<?php

class CreerSaeView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    public function initCreerSaePage($listePersonne,$msg)
    {
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold text-left">CRÉER UNE SAE</h1>
            <div class="card-general shadow bg-white rounded p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <svg width="35" height="35"><use xlink:href="#arrow-icon"></use></svg>
                    </div>
                    <h3 class="fw-bold">Champs à remplir :</h3>
                </div>
                <form action="?module=creerSae&action=submit" method="POST">
                    <div class="mb-3">
                        <label for="nomSae" class="form-label fw-bold">Nom de la SAE</label>
                        <input type="text" class="form-control" id="nomSae" name="nomSae" placeholder="Entrez le nom de la SAE" required>
                    </div>
                    <div class="mb-3">
                        <label for="semestre" class="form-label fw-bold">Semestre</label>
                        <select class="form-control" name="semestre" id="semestre" required>
                            <option disabled selected value="">Choisissez un semestre</option>
                            <option value="1">Semestre 1</option>
                            <option value="2">Semestre 2</option>
                            <option value="3">Semestre 3</option>
                            <option value="4">Semestre 4</option>
                            <option value="5">Semestre 5</option>
                            <option value="6">Semestre 6</option>
                        </select>                        
                    </div>
                    <div class="mb-3">
                        <label for="sujet" class="form-label fw-bold">Sujet</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Entrez le sujet" required>
                    </div>
    
                    <!-- Co-responsables -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Co-responsables</label>
                        <input type="text" class="form-control mb-2" id="searchCoResponsables" placeholder="Rechercher un co-responsable par nom ou prénom">
                        <div class="border p-2 choix-responsable" style="max-height: 150px; overflow-y: auto;" id="coResponsablesList">
    HTML;


                    foreach ($listePersonne as $personne) {
                        if ($personne['estProf']) {
                            $id = htmlspecialchars($personne['idPersonne']);
                            $nomPrenom = htmlspecialchars($personne['prenom'] . ' ' . $personne['nom']);
                            echo <<<HTML
                            <div class="form-check co-responsable-item">
                                <input class="form-check-input" type="checkbox" id="coResponsable$id" name="coResponsables[]" value="$id">
                                <label class="form-check-label" for="coResponsable$id">$nomPrenom</label>
                            </div>
    HTML;
                        }
                    }

                    echo <<<HTML
                        </div>
                        <small class="text-muted">Cochez les co-responsables nécessaires. Laissez vide si aucun.</small>
                    </div>

    
                    <!-- Intervenants -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Intervenants</label>
                        <input type="text" class="form-control mb-2" id="searchIntervenants" placeholder="Rechercher un intervenant par nom ou prénom">
                        <div class="border p-2" style="max-height: 150px; overflow-y: auto;" id="intervenantsList">
    HTML;


                    foreach ($listePersonne as $personne) {
                        if ($personne['estProf']) {
                            $id = htmlspecialchars($personne['idPersonne']);
                            $nomPrenom = htmlspecialchars($personne['prenom'] . ' ' . $personne['nom']);
                            echo <<<HTML
                            <div class="form-check intervenant-item">
                                <input class="form-check-input" type="checkbox" id="intervenant$id" name="intervenants[]" value="$id">
                                <label class="form-check-label" for="intervenant$id">$nomPrenom</label>
                            </div>
    HTML;
                        }
                    }

                    echo <<<HTML
                        </div>
                        <small class="text-muted">Cochez les intervenants nécessaires. Laissez vide si aucun.</small>
                    </div>

    
                    <!-- Ajouter des élèves -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ajouter des élèves</label>
                        <input type="text" class="form-control mb-2" id="searchEleves" placeholder="Rechercher un élève par nom ou prénom">
                        <div class="border p-2" style="max-height: 150px; overflow-y: auto;" id="elevesList">
    HTML;


                    foreach ($listePersonne as $personne) {
                        if (!($personne['estProf'])) {
                            $id = htmlspecialchars($personne['idPersonne']);
                            $nomPrenom = htmlspecialchars($personne['prenom'] . ' ' . $personne['nom']);
                            echo <<<HTML
                            <div class="form-check eleve-item">
                                <input class="form-check-input" type="checkbox" id="eleve$id" name="eleves[]" value="$id">
                                <label class="form-check-label" for="eleve$id">$nomPrenom</label>
                            </div>
    HTML;
                        }
                    }

                    echo <<<HTML
                        </div>
                        <small class="text-muted">Cochez les élèves nécessaires. Laissez vide si aucun.</small>
                    </div>
                    <div>
                        <p class="text-danger">$msg</p>
                    </div>
    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Créer SAE</button>
                    </div>
                </form>
            </div>
        </div>
    HTML;
    }
    function initScript(){
        echo '<script src="js/creersaeview.js"></script>';
    }
}
