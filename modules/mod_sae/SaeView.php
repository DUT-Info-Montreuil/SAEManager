<?php

class SaeView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    // Home

    function initSaePage($lineSae)
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
                    <h3 class="fw-bold">Liste des SAÉs auxquelles vous êtes inscrit :</h3>
                </div>
                $lineSae
            </div>
            </div>

        </div>

        HTML;
    }

    function lineSae($saeName, $idSae)
    {

        return <<<HTML
        <div class="px-5 mx-5 my-3">
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning mx-2 w-25px h-25px"></div>
                <span class="fw-bold mx-1">$saeName</span>
            </div>
            <a href="index.php?module=sae&action=details&idsae=$idSae" class="text-primary text-decoration-none">Accéder à la SAE</a>
            </div>
        </div>


        HTML;
    }

    // Détails

    function initSaeDetails($lineRessource, $lineRendus, $lineSoutenance)
    {
        echo <<<HTML
    <div class="container mt-5">
        <h1 class="fw-bold">SAE DEV WEB</h1>
        <div class="card shadow bg-white rounded p-4">
            <!-- Sujet -->
            <div class="mb-5">
                <h3 class="fw-bold d-flex align-items-center">
                    <svg class="me-2" width="25" height="25">
                        <use xlink:href="#arrow-icon"></use>
                    </svg>
                    Sujet
                </h3>
                <p class="text-muted">Posté le 15/12/2024</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sagittis augue ac mi finibus tincidunt...</p>
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
                    $lineRessource
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
                    $lineRendus
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
                    $lineSoutenance
                </div>
            </div>
        </div>
    </div>
    HTML;
    }

    function lineRessource($ressourceName, $link)
    {
        return <<<HTML
            <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-2">
                    <span>$ressourceName</span>
                        <a href="#" class="ms-auto text-decoration-none text-primary">$link</a>
                    </div>

        HTML;
    }

    function lineRendus($titre, $statut)
    {
        return <<<HTML

        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
                <div class="d-flex align-items-center">
                    <p class="mb-0">$titre</p>
                </div>
                <div class="text-end">
                <p class="text-success mb-0">$statut</p>
                    <a href="#" class="text-primary text-decoration-none">Déposer le rendu</a>
                </div>
            </div>

        HTML;
    }

    function lineSoutenance($titre, $date, $salle)
    {

        return <<<HTML
    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm mb-2">
        <div class="d-flex align-items-center">
            <p class="mb-0">$titre</p>
        </div>
        <div class="text-end">
            <p class="text-muted mb-0">Votre date de passage : $date</p>
            <p class="text-muted mb-1">Salle : $salle</p>
            <a href="#" class="text-primary text-decoration-none fw-bold">Déposer un support</a>
        </div>
    </div>
    HTML;
    }
}
