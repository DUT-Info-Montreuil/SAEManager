<?php

require_once 'GenericModule.php';
require_once 'components/card/CardComponent.php';

class HomeView extends GenericModule
{
    public function __construct()
    {
        $card = new CardComponent('', 'w-100', 'h-75');

        // Ajoute le contenu HTML de la carte et un titre dans l'affichage de la vue
        $this->affichage = <<<HTML
            <div class="container mt-5">
                <h1>ACCUEIL</h1>
                    <p class="card-text">{$card->getAffichage()}</p>
                </div>
            </div>
        HTML;
    }
}
