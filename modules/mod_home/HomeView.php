<?php

require_once 'GenericView.php';

class HomeView extends GenericView
{
    public function __construct()
    {   
        parent::__construct();
    }

    function initHomePage() {
        echo <<<HTML
        <div class="container mt-5">
            <h1>ACCUEIL</h1>
            <div class="card shadow bg-white rounded w-100 h-75">
                <div class="d-flex w-100 h-75 justify-content-center m-auto">
                    <div class="card shadow px-3 rounded w-25 h-75">
                        <div class="card card-sae shadow w-100 h-50"></div>
                        <div class="m-4">
                            <h4>SAÉs</h4>
                            <p>Retrouvez ici la liste de toutes les SAÉs auquel vous avez été associé.</p>
                        </div>
                    </div>
                    <div class="card shadow px-3 mx-5 rounded-bottom w-25 h-75">
                        <div class="card card-rendus shadow w-100 h-50"></div>
                        <div class="m-4">
                            <h4>Rendus</h4>
                            <p>Retrouvez ici la liste de tous les rendus (ceux à rendre et ceux déjà rendus)</p>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>

    HTML;
    }


}
