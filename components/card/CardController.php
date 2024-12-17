<?php

require_once 'components/card/CardView.php';

class CardController
{
    public $vue;

    public function __construct($content = '', $width = 'w-25', $height = 'h-50')
    {
        $this->vue = new CardView($content, $width, $height);
    }

    public function exec()
    {
        return $this->vue;
    }
}
