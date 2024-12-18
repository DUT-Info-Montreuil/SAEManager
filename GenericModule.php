<?php

class GenericModule
{
    protected $affichage;
    protected $title;
    public $controller;

    public function __construct()
    {
        $this->title = "";
        $this->affichage = "";
    }

    public function exec()
    {
        $this->controller->exec();
        $this->affichage = ob_get_clean();
    }

    public function getAffichage()
    {
        return $this->affichage;
    }
}
