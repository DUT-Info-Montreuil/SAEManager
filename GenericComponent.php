<?php

class GenericComponent {

    // Ne pas laisser public
	public $controller;

	public function __construct () {

	}

	public function getAffichage() {
		return $this->controller->vue->getAffichage();

	}
	


}
