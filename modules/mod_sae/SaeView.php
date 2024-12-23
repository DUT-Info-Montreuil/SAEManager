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

    function initSaeDetails($sae, $ressource, $rendus, $soutenance)
    {


        foreach ($sae as $s) {
            $nom = htmlspecialchars($s['nomSae']);
            $dateModif = htmlspecialchars($s['dateModificationSujet']);
            $sujet = htmlspecialchars($s['sujet']);
        }

        foreach ($ressource as $r) {
            $nomRessource = htmlspecialchars($r['contenu']);
        }

        foreach ($rendus as $r) {
            $nomRendu = htmlspecialchars($r['nom']);
            $dateLimite = htmlspecialchars($r['dateLimite']);
        }

        foreach ($soutenance as $s) {
            $titre = htmlspecialchars($s['titre']);
            $dateSoutenance = htmlspecialchars($s['date']);
            $salle = htmlspecialchars($s['salle']);
        }


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
        echo $sujet;
        echo <<<HTML
            </div>

            <!-- Ressource(s) -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Ressource(s)
                </h3>
                <div class="d-flex flex-column">
                    {$this->lineRessource($nomRessource)}
                </div>
            </div>

            <!-- Rendu(s) -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Rendu(s)
                </h3>
                <div class="d-flex flex-column">
                    {$this->lineRendus($nomRendu,$dateLimite)}
                </div>
            </div>

            <!-- Soutenance(s) -->
            <div>
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Soutenance(s)
                </h3>
                <div class="d-flex flex-column">
                    {$this->lineSoutenance($titre,$dateSoutenance,$salle)}
                </div>
            </div>
        </div>
    </div>
    HTML;
    }

    function lineRessource($nomRessource)
    {
        return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>$nomRessource</span>
                        <a href="#" class="ms-auto text-decoration-none text-primary">fichier.pdf</a>
                    </div>

        HTML;
    }

    function lineRendus($nom, $dateLimite)
    {
        return <<<HTML

        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$nom</p>
                </div>
                <div class="text-end">
                <p class="text-danger mb-0">A déposer avant le : $dateLimite</p>
                    <a href="#" class="text-primary text-decoration-none fw-bold">Déposer le rendu</a>
                </div>
            </div>

        HTML;
    }

    function lineSoutenance($titre, $dateSoutenance, $salle)
    {

        return <<<HTML
    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
        <div class="d-flex align-items-center">
            <p class="mb-0">$titre</p>
        </div>
        <div class="text-end">
            <p class="text-muted mb-0">Votre date de passage : $dateSoutenance</p>
            <p class="text-muted mb-1">Salle : $salle</p>
            <a href="#" class="text-primary text-decoration-none fw-bold">Déposer un support</a>
        </div>
    </div>
    HTML;
    }


    /* Groupes */

    function initGroupPage($sae, $groupe, $responsable)
    {

        foreach ($sae as $s) {
            $nomSAE = htmlspecialchars($s['sujet']);
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
            $nom = $membre['nom'];
            $prenom = $membre['prenom'];
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
            $nom = $resp['nom'];
            $prenom = $resp['prenom'];
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
                    SOON...
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
}
