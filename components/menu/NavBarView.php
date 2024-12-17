<?php

require_once 'GenericComponentView.php';
require_once 'assets/icons.svg';

class NavBarView extends GenericComponentView
{

  public function __construct()
  {

    $this->affichage .= <<<HTML
      <div class="menu d-flex text-white">
        <div class="mx-3 p-2 fs-4 flex-1">SAE Manager</div>
        <div class="flex-1 d-flex">
          <div class="my-auto icon"><svg class="icon" width="24" height="24"><use xlink:href="#icon-profile"></use></svg></div>
          <div class="my-auto mx-4">Accueil</div>
        </div>
        <div class="d-flex">
          <button class="my-auto btn btn-link"><svg class="icon" width="24" height="24"><use xlink:href="#logout-icon"></use></svg></div>
          <div class="my-auto mx-2">Se déconnecter</div>
        </div>
        
      </div>
    HTML;
  }
}
