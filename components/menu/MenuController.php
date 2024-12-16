
<?php

require_once 'components/menu/MenuView.php';

class MenuController {

    // Ne pas laisser public
    public $vue;

    public function __construct() {
        $this->vue = new MenuView();
    }

    public function exec() {
        $this->vue;
    }
}