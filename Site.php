<?php

class Site
{

	private $moduleName;
	private $module;

	public function __construct()
	{
		$this->moduleName = isset($_GET['module']) ? $_GET['module'] : "home";

		switch ($this->moduleName) {
			case "home":
				require_once 'modules/mod_home/HomeModule.php';
				break;
			case "sae":
				require_once 'modules/mod_sae/SaeModule.php';
				break;
			default:
				die("Module inexistant");
		}
	}

	public function execModule()
	{
		$moduleClass = $this->moduleName . "Module";
		$this->module = new $moduleClass();
		$this->module->exec();
	}

	public function getModule()
	{
		return $this->module;
	}
}
