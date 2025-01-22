<?php

require_once 'GenericComponentView.php';
require_once 'assets/icons.svg';

class NavBarView extends GenericComponentView
{
	public function __construct()
	{
		$this->affichage .= <<<HTML
		<div class="menu d-flex text-white">
HTML;
		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
			<div class="my-auto icon mx-2 cursor-pointer" id="menu-toggle">
				<svg class="icon" width="24" height="24">
					<use xlink:href="#menu-icon"></use>
				</svg>
			</div>
HTML;
		}
		$this->affichage .= <<<HTML
			<div class="mx-3 p-2 fs-4 flex-1">
				<a href="index.php?module=home" class="text-decoration-none text-reset">SAE Manager</a>
			</div>
HTML;
		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
			<div class="flex-1 d-flex">
				
				<div class="my-auto mx-4">
					<a href="index.php?module=home" class="text-decoration-none text-reset">
						<svg class="icon" width="24" height="24"><use xlink:href="#icon-profile"></use></svg> 
						Accueil
					</a>
				</div>
			</div>
HTML;
		}
		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
            <div class="flex-1 d-flex">
				
			    <div class="my-auto mx-4">
					<a href="index.php?module=dashboard" class="text-decoration-none text-reset">
						<svg class="icon" width="24" height="24"><use xlink:href="#icon-profile"></use></svg> 
						Dashboard
					</a>
				</div>
			</div>
HTML;
		}
		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
			<div class="d-flex">
				<a href="index.php?infoConnexion=deconnexion" class="my-auto btn btn-link mx-2 text-decoration-none text-reset">
					<svg class="icon" width="24" height="24">
						<use xlink:href="#logout-icon"></use>
					</svg> Se déconnecter
				</a>
			</div>
HTML;
		} else {
			$this->affichage .= <<<HTML
			<div class="d-flex">
				<a href="index.php?module=connexion" class="my-auto mx-2 text-decoration-none text-reset">Se connecter</a>
			</div>
HTML;
		}
		$this->affichage .= <<<HTML
		</div>

		<div id="side-menu" class="side-menu">
HTML;

		$module = $_GET['module'] ?? '';
		$action = $_GET['action'] ?? '';

        $estProfUtilisateur = isset($_SESSION['loginUtilisateur']) && $_SESSION['estProfUtilisateur'] == 1;

		if ($module == 'sae' && $action != 'home') {
			$saeID = $_GET['id'] ?? "";
			$this->affichage .= <<<HTML
			<ul>
				<li><a href="index.php?module=home">Accueil</a></li>
				<li>Général</li>
				<li><a href="index.php?module=home">Sujet</a></li>
				<li><a href="index.php?module=sae&action=ressources">Ressources</a></li>
HTML;
            if($estProfUtilisateur)
                $this->affichage .= <<<HTML
				<li><a href="index.php?module=sae&action=listeRendusGroupe&id=$saeID">Rendus</a></li>
HTML;
                $this->affichage .= <<<HTML
				<li><a href="index.php?module=sae&action=soutenance&id=$saeID">Soutenance</a></li>
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
