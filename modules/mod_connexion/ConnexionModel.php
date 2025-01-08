
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
        if(isset($_SESSION['loginUtilisateur'])){
            unset($_SESSION['loginUtilisateur']);
            protectionCSRF::supprimerToken();
            echo "Vous êtes bien déconnecté";
        }
        else{
            echo 'Vous n\'êtes pas connecté ! Pour se connecter : <a href="index.php">Se connecter</a>';
        }
        
    }

}


?>