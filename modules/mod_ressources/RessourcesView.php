
<?php
//Tout droit réservée
//All right reserved
//Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN

class RessourcesView extends GenericView
{


    function initRessources($ressources, $myRessources)
    {
        $myRessourcesIds = array_column($myRessources, 'idRessource');
        $isProf = isset($_SESSION['estProfUtilisateur']) && $_SESSION['estProfUtilisateur'];

        echo <<<HTML
    <div class="container mt-5 h-100">
        <h1 class="fw-bold">LISTE DES RESSOURCES</h1>
        <div class="card-general shadow bg-white rounded min-h75 p-3">
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
