<?php

require_once 'GenericComponentView.php';

class MenuView extends GenericComponentView {

    public function __construct(){
		$this->affichage .= '<div class="menu d-flex">';
		$this->affichage .= '<div class="mx-3 p-2 fs-4 flex-1">SAE Manager</div>';
        $this->affichage .= '<div class="flex-1 my-auto"> Accueil</div>';
        $this->affichage .= '<div class="my-auto mx-2"> Se deconnecter</div>';


	}	

}

