<?php

require_once 'GenericComponentView.php';
require_once 'assets/icons.svg';

class NavBarView extends GenericComponentView
{
  public function __construct()
  {
    $this->affichage .= <<<HTML
    <div class="menu d-flex text-white">
      <div class="my-auto icon mx-2 cursor-pointer" id="menu-toggle"><svg class="icon" width="24" height="24"><use xlink:href="#menu-icon"></use></svg></div>
      <div class="mx-3 p-2 fs-4 flex-1"><a href="index.php?module=home" class="text-decoration-none text-reset">SAE Manager</a></div>
      <div class="flex-1 d-flex">
        <div class="my-auto icon"><svg class="icon" width="24" height="24"><use xlink:href="#icon-profile"></use></svg></div>
        <div class="my-auto mx-4"><a href="index.php?module=home" class="text-decoration-none text-reset">Accueil</a></div>
      </div>
      <div class="d-flex">
        <button class="my-auto btn btn-link"><svg class="icon" width="24" height="24"><use xlink:href="#logout-icon"></use></svg></button>
        <p class="my-auto mx-2"><a href="" class="text-decoration-none text-reset">Se déconnecter</a></p>
      </div>
    </div>

<div id="side-menu" class="side-menu">
HTML;

    $module = $_GET['module'] ?? '';
    $action = $_GET['action'] ?? '';


    if ($module == 'sae' && $action != 'home') {

      $saeID = $_GET['id'];
      $this->affichage .= <<<HTML
      <ul>
        <li><a href="index.php?module=home">Accueil</a></li>
        <li>Général</li>
        <li><a href="index.php?module=home">Sujet</a></li>
        <li><a href="index.php?module=home">Ressources</a></li>
        <li><a href="index.php?module=home">Rendus</a></li>
        <li><a href="index.php?module=home">Soutenance</a></li>
        <li><a href="index.php?module=sae&action=groupe&id=$saeID">Groupe</a></li>
        <li><a href="index.php?module=home">Cloud</a></li>
        <li><a href="index.php?module=home">Messages</a></li>
        <li><a href="index.php?module=sae&action=note&id=$saeID">Notes</a></li>
      </ul>
      </div>
HTML;
    } else {

      $this->affichage .= <<<HTML
      <ul>
        <li><a href="index.php?module=home">Accueil</a></li>
        <li><a href="index.php?module=sae&action=home">SAÉs</a></li>
        <li><a href="index.php?module=rendus&action=home">Rendus</a></li>
      </ul>
      </div>
HTML;
    }
  }
}
