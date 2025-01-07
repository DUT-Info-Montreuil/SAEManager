<?php

class protectionCSRF{
    public static function genererToken(){
        if(!isset($_SESSION['token_csrf'])){
            $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['token_csrf'];
    }

    static function genererTokenInput(){
        return '<input type="hidden" name="token_csrf" value="'.self::genererToken().'"></input>';
    }

    static function validerToken($token){
        return isset($_SESSION['token_csrf']) && hash_equals($_SESSION['token_csrf'],$token);
    }

    static function supprimerToken(){
        unset($_SESSION['token_csrf']);
    }



}
?>
