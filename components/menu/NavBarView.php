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
		$this->affichage .= <<<HTML
			<div class="mx-3 p-2 fs-4 flex-1">
				<a href="index.php?module=home" class="text-decoration-none text-reset">SAE Manager</a>
			</div>
HTML;
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

		<div class="sous-menu py-2 d-flex text-white">
HTML;

		$module = $_GET['module'] ?? '';
		$action = $_GET['action'] ?? '';

		if ($module == 'sae' && $action != 'home' && isset($_SESSION['loginUtilisateur'])) {
			$saeID = $_GET['id'] ?? "";
			$this->affichage .= <<<HTML
				<a href="index.php?module=home" class="my-auto mx-3 text-decoration-none text-reset">Accueil</a>
				<a href="index.php?module=sae&action=home" class="my-auto mx-3 text-decoration-none text-reset">SAÉs</a>
				<a href="index.php?module=rendu" class="my-auto mx-3 text-decoration-none text-reset">Rendus</a>
				<a href="index.php?module=sae&action=ressources" class="my-auto mx-3 text-decoration-none text-reset">Ressources</a>
				<a href="index.php?module=sae&action=soutenance&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Soutenance</a>
				<a href="index.php?module=sae&action=groupe&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Groupe</a>
				<a href="index.php?module=sae&action=cloud&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Cloud</a>
				<a href="index.php?module=sae&action=note&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Notes</a>
			
			</div>
HTML;
		} elseif (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
					<a href="index.php?module=home" class="my-auto mx-3 text-decoration-none text-reset">Accueil</a>
					<a href="index.php?module=sae&action=home" class="my-auto mx-3 text-decoration-none text-reset">SAÉs</a>
					<a href="index.php?module=rendus&action=home" class="my-auto mx-3 text-decoration-none text-reset">Rendus</a>			
			</div>
HTML;
		}
	}
}
