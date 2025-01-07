<?php

require_once 'GenericView.php';

class ConnexionView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    function initConnexionPage()
    {

        echo <<<HTML
        <div class="container mt-5">
            <h1 class="fw-bold">CONNEXION</h1>
            <div class="card shadow bg-white rounded w-100 h-75">
                <div class="d-flex w-100 h-75 justify-content-center m-auto">
                    {$this->cardConnexion()}
                </div>
                
            </div>

        </div>

    HTML;
    }

    function cardConnexion()
    {
        return '
            <form action="index.php?infoConnexion=essaieConnexion" id="formConnexion" method="POST">
                <div>
                    <div>
                        <label for="" name="login">identifiant : </label>
                        <input type="text" name="login">
                        <label for="" name="password">mot de passe : </label>
                        <input type="password" name="password" required>
                        '.protectionCSRF::genererTokenInput().'
                    </div>
                    <label for="" name="login">Pas de compte ?</label>
                    <a href="index.php?infoConnexion=inscription">S\'inscrire</a>
                    
                </div>
                <button type="submit">Se connecter</button> <br>
            </form>';
    }
}

?>