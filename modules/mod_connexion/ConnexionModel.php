
<?php

require_once "connexion.php";

class ConnexionModel extends Connexion {
    public function __construct () {
        self::initConnexion();
    }

    function essaieConnexion(){
        $sql = self::$bdd -> prepare('SELECT * from Personne WHERE login = ?');
        $sql -> execute([$_POST['login']]);

        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if($result){ //&& password_verify($_POST['password'],$result['password'])&&protectionCSRF::validerToken($_POST['token_csrf'])
            $_SESSION['loginUtilisateur'] = $result['login'];
            return true;
        } else{
            return false;
        }
    }

    function deconnexion(){
        var_dump("test");
        var_dump($_SESSION['loginUtilisateur']);

        if(isset($_SESSION['loginUtilisateur'])){
            var_dump("test1");

            unset($_SESSION['loginUtilisateur']);
            var_dump("test2");

            protectionCSRF::supprimerToken();
            var_dump("test3");

            return true; //La déconnexion c'est bien faite
        }
        else{
            var_dump("test4");

            return false; //l'utilisateur essaie d'accéder à "deconnexion" sachant qu'il est déjà déconnecté
        }
        
    }

}


?>