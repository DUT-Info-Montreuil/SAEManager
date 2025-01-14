<?php

require_once 'GenericView.php';

class ConnexionView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    function deconnexionPage($msg){
        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">DÉCONNEXION</h1>
            <div class="card shadow bg-white rounded w-100 h-75">
                <div class="d-flex w-100 h-75 justify-content-center m-auto">
                $msg
                </div>
                
            </div>

        </div>

HTML;
    }

    function connexionPage($msg_erreur){
        echo <<<HTML
<div class="container mt-5">
    <h1 class="fw-bold text-center text-md-start">CONNEXION</h1>
    <div class="card shadow bg-white rounded w-100 d-flex flex-column flex-md-row" 
         style="height: auto; max-height: 75%;">
        <!-- Partie gauche : images -->
        <div class="images-container d-flex position-relative mx-auto mx-md-0" 
             style="flex: 1; overflow: hidden; max-width: 100%; height: auto;">
            <div style="
                        width: 199px;
                        height: 365px;
                        left: 83px;
                        top: 12px;
                        position: absolute;
                        background: linear-gradient(180deg, #E54C91 0%, #F48B7B 50%, #F7AD50 100%);
                        box-shadow: 0px 9.923077583312988px 39.69231033325195px 9.923077583312988px rgba(0, 0, 0, 0.25);
                        border-radius: 49.62px;
                        ">
            </div>
            <img src="assets/img/Vector1.png" style="width: 142px;height: 283px;left: 125px;top: 74px;position: absolute;" />
            <img src="assets/img/Saly-13.png" style="width: 320px;height: 311px;left: 49px;top: 41px;position: absolute;" />
        </div>

        <!-- Partie droite : formulaire -->
        <div class="form-container d-flex flex-column justify-content-center align-items-center px-3" 
             style="flex: 1; padding: 20px; max-width: 100%; height: auto;">
            <form action="index.php?module=connexion&infoConnexion=essaieConnexion" id="formConnexion" method="POST" style="width: 100%;">
                <div class="mb-3 text-center">
                    <span>Bienvenue sur </span>
                    <span style="background: linear-gradient(90deg, #E54C91 0%, #F48B7B 50%, #F7AD50 100%);
                                 -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        SAÉ MANAGER
                    </span>
                    <span>, l’outil qui va te changer la vie !</span>
                </div>

                <p class="text-danger text-center">
                    $msg_erreur
                </p>

                <div class="mb-3">
                    <label for="login" class="form-label">Identifiant :</label>
                    <input type="text" name="login" class="form-control" id="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <!-- Protection CSRF -->
HTML .
                protectionCSRF::genererTokenInput() .
                <<<HTML

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .card {
        height: auto;  /* Par défaut, auto */
    }

    @media (min-width: 768px) { /* Pour les écrans plus grands */
        .card {
            height: 75%!important; /* Hauteur fixée à 75% */
        }
    }
</style>
HTML;

    }
}
