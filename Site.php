<?php
require_once 'modules/mod_connexion/ConnexionModule.php';


class Site
{

	private $moduleName;
	private $module;

	public function __construct()
	{
		$this->moduleName = isset($_GET['module']) ? $_GET['module'] : "dashboard";

		if (!isset($_SESSION['loginUtilisateur'])) {
			$this->moduleName = "connexion";
		} else if (isset($_SESSION['loginUtilisateur']) && $this->moduleName == "connexion") {
			$this->moduleName = "dashboard";
		}

		$infoConnexion = isset($_GET['infoConnexion']) ? $_GET['infoConnexion'] : null;

		if ($infoConnexion) {
			$moduleConnexion = new ConnexionModule();
			$moduleConnexion->exec();

		} else {
			switch ($this->moduleName) {
				case "connexion":
					if (!isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_connexion/ConnexionModule.php';
					}
					break;
				case "home":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_home/HomeModule.php';
					}
					break;
				case "sae":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_sae/SaeModule.php';
					}
					break;
				case "rendus":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_rendus/RendusModule.php';
					}
					break;
				case "soutenance":
					if(isset($_SESSION['loginUtilisateur'])){
						require_once 'modules/mod_soutenance/SoutenanceModule.php';
					}
					break;
				case "creerSae":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_creerSae/CreerSaeModule.php';
					}
					break;
				case "dashboard":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_dashboard/DashboardModule.php';
					}
					break;
				case "ressources":
					if (isset($_SESSION['loginUtilisateur'])) {
						require_once 'modules/mod_ressources/RessourcesModule.php';
					}
					break;
                case "paneladmin":
                    if(isset($_SESSION['loginUtilisateur']) && isset($_SESSION['estAdmin']) && $_SESSION['estAdmin'] == 1){
                        require_once 'modules/mod_paneladmin/PanelAdminModule.php';
                    }
                    break;
				default:
					if (isset($_SESSION['loginUtilisateur'])) {
						die("Module inexistant");
					}
					break;
			}
		}
	}

	public function execModule()
	{
		if (!isset($_SESSION['loginUtilisateur'])) {
			$this->moduleName = "connexion";
		} else if (isset($_SESSION['loginUtilisateur']) && $this->moduleName == "connexion") {
			$this->moduleName = "dashboard";
		}


		$moduleClass = $this->moduleName . "Module";
		$this->module = new $moduleClass();
		$this->module->exec();
	}

	public function getModule()
	{
		return $this->module;
	}
}
