<?php

require_once 'components/footer/FooterView.php';

class FooterController
{
    public $vue;

    public function __construct()
    {
        $this->vue = new FooterView();
    }

    public function exec()
    {
        $this->vue;
    }
}
