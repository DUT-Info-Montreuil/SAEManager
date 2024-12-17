
<?php

require_once 'components/menu/NavBarView.php';

class NavBarController {

    // Ne pas laisser public
    public $vue;

    public function __construct() {
        $this->vue = new NavBarView();
    }

    public function exec() {
        $this->vue;
    }
}