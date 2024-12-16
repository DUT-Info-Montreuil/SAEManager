<?php

class Connexion {

    protected static $bdd;
    
    static function initConnexion() {
        self::$bdd = new PDO('mysql:host=localhost;dbname=web', 'root', 'root');
    }

}