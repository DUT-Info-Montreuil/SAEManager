<?php

class RendusView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    public function initRendusPage($rendus)
    {



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
                    <div class="d-flex flex-column">
                        <h3 class="fw-bold">Liste des différents rendus des SAÉs auxquelles vous êtes inscrit(e) :</h3>
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
}
