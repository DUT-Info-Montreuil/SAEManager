<?php

require_once 'components/card/CardController.php';

class CardComponent extends GenericComponent
{

    // Ne pas laisser public
    public $controller;

    public function __construct($content = '', $width = 'w-25', $height = 'h-50')
    {
        parent::__construct();
        $this->controller = new CardController($content, $width, $height);
    }
}
