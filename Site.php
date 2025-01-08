<?php

class Site
{

	private $moduleName;
	private $module;

	public function __construct()
	{
		$this->moduleName = isset($_GET['module']) ? $_GET['module'] : "home";
		if(!isset($_SESSION['loginUtilisateur'])){
			$this->moduleName = "connexion";
		}
		
		switch ($this->moduleName) {
			case "connexion":
				if(isset($_SESSION['loginUtilisateur'])){
					require_once 'modules/mod_home/HomeModule.php';
                }else{
					require_once 'modules/mod_connexion/ConnexionModule.php';
				}
				break;
			case "home":
				if(isset($_SESSION['loginUtilisateur'])){
					require_once 'modules/mod_home/HomeModule.php';
                }
				break;
			case "sae":
				if(isset($_SESSION['loginUtilisateur'])){
					require_once 'modules/mod_sae/SaeModule.php';
                }
				break;
			case "rendus":
				if(isset($_SESSION['loginUtilisateur'])){
					require_once 'modules/mod_rendus/RendusModule.php';
                }
				break;
			default:
				if(isset($_SESSION['loginUtilisateur'])){
					die("Module inexistant");
				}
				break;
				
		}
	}

	public function execModule(){

		$moduleClass = $this->moduleName . "Module";
		$this->module = new $moduleClass();
		$this->module->exec();

	}

	public function getModule()
	{
		return $this->module;
	}
}
