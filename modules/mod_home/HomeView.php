<?php

require_once 'GenericView.php';

class HomeView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    function initHomePage()
    {


        $toast = "";
        if (isset($_SESSION['connexion_reussie']) && $_SESSION['connexion_reussie'] === true) {
            $toast = <<<HTML
    <div class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Connexion réussie !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
HTML;
            unset($_SESSION['connexion_reussie']);
        }
        if ($_SESSION["estProfUtilisateur"] == 1) {
            echo <<<HTML
            <div class="container mt-5 h-100">
                <h1 class="fw-bold">ACCUEIL</h1>
                <div class="card-general shadow bg-white rounded w-100 m-auto">
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex w-100 m-auto mt-5">
                            {$this->cardSAE()}
                            {$this->cardRendus()}
                            {$this->cardCreerSAE()}
                        </div>
                        <div class="d-flex w-100 m-auto mt-5 mb-5">   
                            {$this->cardRessource()}
                            {$this->cardSoutenance()}

                        </div>
                    </div>
                </div>
            </div>
HTML;
        } else { //Est un élève
            echo <<<HTML
            <div class="container mt-5 h-100">
                <h1 class="fw-bold">ACCUEIL</h1>
                <div class="card-general shadow bg-white rounded w-100 m-auto min-h75">
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex w-100 m-auto mt-5">
                            {$this->cardSAE()}
                            {$this->cardRendus()}
                            {$this->cardRessource()}
                        </div>
                        <div class="d-flex w-100 m-auto mt-5 mb-5">   
                        </div>
                    </div>
                </div>
            </div>
HTML;
        }

        echo <<<HTML
        $toast
        <script src="js/toast.js"></script>
HTML;
    }

    function cardSAE()
    {

        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded w-25 card-sae-content">
            <a href="index.php?module=sae&action=home" class="text-decoration-none text-reset">
                <div class="card card-sae shadow w-100" style="min-height: 150px;">
                </div>
                <div class="m-4">
                    <h4>SAÉs</h4>
                    <p>Retrouvez ici la liste de toutes les SAÉs auxquelles vous avez été associé.</p>
                </div>
            </a>
        </div>
HTML;
    }

    function cardRendus()
    {
        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 card-rendus-content">
            <a href="index.php?module=rendus&action=home" class="text-decoration-none text-reset">
                <div class="card card-rendus shadow w-100" style="min-height: 150px;">
                </div>
                <div class="m-4">
                    <h4>Rendus</h4>
                    <p>Retrouvez ici la liste de tous les rendus (ceux à rendre et ceux déjà rendus)</p>
                </div>
            </a>
        </div>
HTML;
    }

    function cardCreerSAE()
    {
        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 card-rendus-content">
            <a href="index.php?module=creerSae&action=home" class="text-decoration-none text-reset">
                <div class="card card-create-sae shadow w-100" style="min-height: 150px;">
                </div>
                <div class="m-4">
                    <h4>Créer une SAÉ</h4>
                    <p>Créer un nouveau sujet de SAE.</p>
                </div>
            </a>
        </div>
HTML;
    }

    function cardRessource()
    {

        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 card-rendus-content">
            <a href="index.php?module=ressources&action=home" class="text-decoration-none text-reset">
                <div class="card card-ressource shadow w-100" style="min-height: 150px;"></div>
                <div class="m-4">
                    <h4>Ressources</h4>
                    <p>Accéder à vos Ressources</p>
                </div>
            </a>
        </div>
HTML;
    }
    function cardSoutenance(){
        return <<<HTML
        <div class="card shadow px-3 mx-5 rounded-bottom w-25 card-rendus-content">
            <a href="index.php?module=soutenance&action=home" class="text-decoration-none text-reset">
                <div class="card card-soutenance shadow w-100" style="min-height: 150px;"></div>

                <div class="m-4">
                    <h4>Soutenances</h4>
                    <p>Retrouvez ici la liste de toutes les soutenances.</p>
                </div>
            </a>
        </div>
        HTML;
    }
}
