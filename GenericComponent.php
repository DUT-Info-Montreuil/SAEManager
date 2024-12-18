<?php

class GenericComponent {

	protected $controller;

	public function __construct () {

	}

	public function getAffichage() {
		return $this->controller->vue->getAffichage();

	}
	


}
