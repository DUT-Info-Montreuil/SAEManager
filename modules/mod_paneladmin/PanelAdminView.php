<?php

class PanelAdminView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    public function initPanel($listeCompteNonProf, $listeProf) {
        $inputs = '';
        foreach ($listeCompteNonProf as $personne) {
            $nom = $personne['nom'];
            $prenom = $personne['prenom'];
            $inputs .= '
            <div class="form-check personnes dropdown-item">
                <input class="form-check-input" name="personnes[]" type="checkbox" value="' . $personne['idPersonne'] . '" id="' . $personne['idPersonne'] . '">
                <label class="form-check-label" for="' . $personne['idPersonne'] . '">' . $prenom . ' ' . $nom . '</label>
            </div>';
        }



        $profInputs = '';
        foreach ($listeProf as $prof) {
            $nomProf = $prof['nom'];
            $prenomProf = $prof['prenom'];
            $profInputs .= '
            <div class="form-check professeurs dropdown-item">
                <input class="form-check-input" name="professeurs[]" type="checkbox" value="' . $prof['idPersonne'] . '" id="prof_' . $prof['idPersonne'] . '">
                <label class="form-check-label" for="prof_' . $prof['idPersonne'] . '">' . $prenomProf .' '. $nomProf . '</label>
            </div>';
        }

        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">PANEL ADMIN</h1>
            <div class="card-general shadow bg-white rounded">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row h-50 m-3 p-3 w-100">
                        <div class="d-flex flex-column m-3 bg-light border rounded w-50">
                            <h3 class="m-3 fw-bold">Ajoutez une ou des personne en tant que professeur</h3>
                            <form action="index.php?module=paneladmin&action=ajouterProfesseur" class="m-3" method="POST">
                                <div class="d-flex flex-column text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="dropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Rechercher des personnes non professeurs
                                        </button>
                                        <div class="dropdown-menu p-3 gap-1 w-100" id="dropdownContent" style="max-height: 400px; overflow-y: auto;">
                                            <input type="text" class="form-control mb-2" id="searchInput" placeholder="Rechercher...">
                                            <!-- Les éléments de la liste déroulante -->
                                            $inputs
                                        </div>
                                    </div>
                                    <div class="text-danger mt-2" id="errorMessage" style="display: none;">
                                        Vous devez sélectionner au moins un étudiant.
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success m-3">Valider</button>
                                        <button type="button" class="btn btn-danger m-3" onclick="annulerAction()">Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
        
                        <div class="d-flex flex-column m-3 bg-light border rounded w-50">
                            <h3 class="m-3 fw-bold">Supprimer un ou des professeurs déjà existants</h3>
                            <form action="index.php?module=paneladmin&action=suprimmerProf" class="m-3" method="POST">
                                <div class="d-flex flex-column text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="dropdownButtonProf" data-bs-toggle="dropdown" aria-expanded="false">
                                            Rechercher parmi les professeurs existants
                                        </button>
                                        <div class="dropdown-menu p-3 gap-1" id="dropdownContentProf" style="max-height: 400px; overflow-y: auto;">
                                            <input type="text" class="form-control mb-2" id="searchInputProf" placeholder="Rechercher...">
                                            <!-- Les éléments de la liste déroulante pour les professeurs existants -->
                                            $profInputs
                                        </div>
                                    </div>
                                    <div class="text-danger mt-2" id="errorMessageProf" style="display: none;">
                                        Vous devez sélectionner au moins un professeur.
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success m-3">Valider</button>
                                        <button type="button" class="btn btn-danger m-3" onclick="annulerAction()">Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex flex-column m-3 bg-light border rounded overflow-auto p-3">
                        <h3 class="m-3 fw-bold">Liste des professeurs existants</h3>
                        <div class="d-flex flex-column overflow-auto" style="max-height: 300px;">
        HTML;

                foreach($listeProf as $prof) {
                    $nom = $prof['nom'];
                    echo <<<HTML
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">$nom</p>
                                    </div>
                                </div>
        HTML;
                }

                echo <<<HTML
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="js/paneladmin.js"></script>
HTML;
    }

}
