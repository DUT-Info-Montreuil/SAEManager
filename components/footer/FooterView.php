<?php

require_once 'GenericComponentView.php';

class FooterView extends GenericComponentView
{
    public function __construct()
    {
        $currentYear = date("Y");
        $this->affichage = <<<HTML
        <div class="footer bg-dark text-white py-4 mt-auto">
            <div class="container text-center">
                &copy; $currentYear SAE Manager. Tous droits réservés.
            </div>
        </div>
HTML;
    }
}
