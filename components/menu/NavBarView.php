<?php

require_once 'GenericComponentView.php';
require_once 'assets/icons.svg';

class NavBarView extends GenericComponentView
{

  public function __construct()
  {

    $this->affichage .= <<<HTML
      <div class="menu d-flex text-white">
        <div class="mx-3 p-2 fs-4 flex-1"><a href="index.php?module=home" class="text-decoration-none text-reset">SAE Manager</a></div>
        <div class="flex-1 d-flex">
          <div class="my-auto icon"><svg class="icon" width="24" height="24"><use xlink:href="#icon-profile"></use></svg></div>
          <div class="my-auto mx-4"><a href="index.php?module=home" class="text-decoration-none text-reset">Accueil</a></div>
        </div>
        <div class="d-flex">
          <button class="my-auto btn btn-link"><svg class="icon" width="24" height="24"><use xlink:href="#logout-icon"></use></svg></div>
          <p class="my-auto mx-2"><a href="" class="text-decoration-none text-reset">Se déconnecter</a></p>
        </div>
        
      </div>
    HTML;
  }
}
