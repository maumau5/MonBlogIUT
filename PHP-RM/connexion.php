<?php
require_once 'config/init.conf.php';
require_once 'include/fonctions.inc.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.conf.php';
include_once 'include/header.inc.php';

if(!empty($_POST['submit'])){ //si on a appuyé sur le bouton de soumission du formulaire alors
    //print_r2($_POST);
    //exit();
    
    $email = filter_input(INPUT_POST, 'email');
    $mdp = sha1(filter_input(INPUT_POST, 'mdp')); //Calcule le sha1 d'une chaîne de caractères, ici le mot de passe
    
    $sth = $bdd->prepare("SELECT * " //on prépare la requête d'insertion de l'article dans la base de données
            . "FROM user "
            . "WHERE email = :email AND mdp = :mdp");
    $sth->bindValue(':email', $email, PDO::PARAM_STR);
    $sth->bindValue(':mdp', $mdp, PDO::PARAM_STR);
    
    $sth->execute(); //on exécute la requête préparée au préalable (retourne true si tout s'est bien passé, sinon erreur)
    
    if ($sth->rowCount() > 0) {
        //la connexion est OK
        $donnees = $sth->fetch(PDO::FETCH_ASSOC);
        //print_r2($donnees);
        $sid = $donnees['email'] . time();
        $sid_hache = md5($sid);
        //echo $sid_hache;
        
        setcookie('sid', $sid_hache, time() + 3600);
        
        $sth_update = $bdd->prepare("UPDATE user "
                . "SET sid = :sid "
                . "WHERE id = :id");
        
        $sth_update->bindValue(':sid', $sid_hache, PDO::PARAM_STR);
        $sth_update->bindValue(':id', $donnees['id'], PDO::PARAM_INT);
        
        $result_connexion = $sth_update->execute();
        //var_dump($sth_update);
        
        /*         * *** NOTIFICATIONS **** */
        if ($result_connexion == TRUE){
            $_SESSION['notification']['result'] = 'success';
            $_SESSION['notification']['message'] = '<b>Felicitations ! </b> Vous êtes connecté.';
        }else{
            $_SESSION['notification']['result'] = 'danger';
            $_SESSION['notification']['message'] = '<b>Attention !</b> Une erreur s\'est produite. ';
        }
        
        //Redirection vers l'accueil
        header("location: index.php");
        exit();
    }else {
        //la connexion est refusée
        /*         * *** NOTIFICATIONS **** */
        
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = '<b>Attention !</b> Veuillez vérifier vos identifiant et mot de passe.';
        
        //Redirection vers l'accueil
        header("location: connexion.php");
        exit();
    }
    
    //exit();//fin de l'exécution du script
}else{
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Connexion</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="POST" action="connexion.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="email">Votre adresse mail</label>
                    <input type="email" required class="form-control" id="email" name="email" placeholder="example@gmail.com">
                </div>
                <div class="form-group">
                    <label for="mdp">Votre mot de passe</label>
                    <input type="password" required class="form-control" id="texte" rows="3" name="mdp" placeholder="Entrer ici votre mot de passe">
                </div>
                <button type="submit" class="btn btn-primary" name="submit" value="bouton">Connexion</button>
            </form>
        </div>
    </div>


</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.slim.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
}
