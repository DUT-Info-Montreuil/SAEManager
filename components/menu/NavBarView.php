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
					<a href="index.php?module=dashboard&action=home" class="text-decoration-none text-reset">
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
HTML;
		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
		<div class="sous-menu py-2 d-flex text-white">
HTML;
		}
		$module = $_GET['module'] ?? '';
		$action = $_GET['action'] ?? '';

		$estProfUtilisateur = isset($_SESSION['loginUtilisateur']) && $_SESSION['estProfUtilisateur'] == 1;

		if (isset($_SESSION['loginUtilisateur'])) {
			$this->affichage .= <<<HTML
					<a href="index.php?module=home" class="my-auto mx-3 text-decoration-none text-reset">Accueil</a>
					<a href="index.php?module=sae&action=home" class="my-auto mx-3 text-decoration-none text-reset">SAÉs</a>
					<a href="index.php?module=rendus&action=home" class="my-auto mx-3 text-decoration-none text-reset">Rendus</a>

HTML;
			if (isset($_SESSION['estAdmin']))
				$this->affichage .= <<<HTML
                <a href="index.php?module=paneladmin&action=home" class="my-auto mx-3 text-decoration-none text-reset">Panel Admin</a>
HTML;
			if ($module == 'sae' && $action != 'home') {
				$saeID = $_GET['id'] ?? "";
				$this->affichage .= <<<HTML
				|<a href="index.php?module=sae&action=details&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Détails SAE</a>
				<a href="index.php?module=sae&action=groupe&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Groupe</a>
HTML;
				if ($estProfUtilisateur)
					$this->affichage .= <<<HTML
				<a href="index.php?module=sae&action=listeRendusGroupe&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Fichiers des rendus</a>
				<a href="index.php?module=sae&action=reponsesAuxChamps&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Réponses aux champs</a>
				<a href="index.php?module=sae&action=listeSupportGroupe&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Supports des soutenances</a>
				<a href="index.php?module=sae&action=soutenance&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Soutenance</a>

HTML;
				if (!$estProfUtilisateur)
					$this->affichage .= <<<HTML
				<a href="index.php?module=sae&action=cloud&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Cloud</a>
				<a href="index.php?module=sae&action=note&id=$saeID" class="my-auto mx-3 text-decoration-none text-reset">Notes</a>
HTML;
			}
			$this->affichage .= <<<HTML
			</div>
HTML;
		}
	}
}
