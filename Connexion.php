<?php

class Connexion
{

    protected static $bdd;

    static function initConnexion()
    {
        self::$bdd = new PDO('mysql:host=database-etudiants.iut.univ-paris8.fr;dbname=dutinfopw201626', "dutinfopw201626", "nasesyhy");
    }
}
