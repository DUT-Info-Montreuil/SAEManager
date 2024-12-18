<?php

class SaeView extends GenericView
{

    public function __construct()
    {
        parent::__construct();
    }

    function initSaePage()
    {

        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">LISTE DES SAÉ(S)</h1>
            <div class="card shadow bg-white rounded">
                <div class="d-flex align-items-center p-5 mx-5">
                    <div class="me-3">
                        <svg width="35" height="35">
                        <use xlink:href="#arrow-icon"></use>
                        </svg>
                    </div>
                    <h3 class="fw-bold">Liste des SAÉs auxquelles vous êtes inscrit :</h3>
                </div>
                
                
                {$this->cardLineSae()}
                {$this->cardLineSae()}
                {$this->cardLineSae()}
                {$this->cardLineSae()}
                {$this->cardLineSae()}
                {$this->cardLineSae()}
                {$this->cardLineSae()}
            </div>
                
            </div>

        </div>

        HTML;
    }

    function cardLineSae()
    {

        return <<<HTML
        <div class="px-5 mx-5 my-4">
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 shadow-sm w-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning mx-2 w-25px h-25px"></div>
                <span class="fw-bold mx-1">SAE DEV WEB</span>
            </div>
            <a href="#" class="text-primary text-decoration-none">Accéder à la SAE</a>
            </div>
        </div>


        HTML;
    }
}
