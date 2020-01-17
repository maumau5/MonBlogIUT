<?php
require_once 'config/init.conf.php';
require_once 'include/fonctions.inc.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.conf.php';
include_once 'include/header.inc.php';

/* @var $bdd PDO */

if(!empty(filter_input(INPUT_POST, 'submit'))){ //si on a appuyé sur le bouton de soumission du formulaire alors
    //print_r2(filter_input_array(INPUT_POST));
    //print_r2(filter_input_array(INPUT_POST));
    
    $nom = filter_input(INPUT_POST, 'nom');
    $prenom = filter_input(INPUT_POST, 'prenom');
    $email = filter_input(INPUT_POST, 'email');
    $mdp = sha1(filter_input(INPUT_POST, 'mdp'));//cryptage du mot de passe
    
    $sth = $bdd->prepare("INSERT INTO user " //on prépare la requête d'insertion de l'article dans la base de données
            . "(nom, prenom, email, mdp) "
            . "VALUES (:nom, :prenom, :email, :mdp)");
    $sth->bindValue(':nom', $nom, PDO::PARAM_STR);
    $sth->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $sth->bindValue(':email', $email, PDO::PARAM_STR);
    $sth->bindValue(':mdp', $mdp, PDO::PARAM_STR);
    
    $sth->execute(); //on exécute la requête préparée au préalable (retourne true si tout s'est bien passé, sinon erreur)
    
    //$id_user = $bdd->lastInsertId(); //on récupère et on donne à la variable $id_user le dernier id inseré dans la base de données
    
    $message = '<b>Félicitation</b>, votre compte a été créé !'; //message de notification affiché sur la page d'accueil lorsqu'un article s'est bien ajouté à la base de données
    $result = 'success'; //pour savoir si l'envoi de l'article s'est bien passé ou pas
    
    declareNotification($message, $result);
    
    header("Location: index.php"); //on redirige l'utilisateur à la page d'accueil lorsqu'il a inséré un article.
    
    exit();//fin de l'exécution du script
}
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Inscription d'un utilisateur</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="POST" action="user.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom">Votre nom</label>
                    <input type="text" required class="form-control" id="nom" name="nom">
                </div>
                <div class="form-group">
                    <label for="prenom">Votre prénom</label>
                    <input type="text" required class="form-control" id="prenom" name="prenom">
                </div>
                <div class="form-group">
                    <label for="email">Votre adresse mail</label>
                    <input type="email" required class="form-control" id="email" name="email" placeholder="example@gmail.com">
                </div>
                <div class="form-group">
                    <label for="mdp">Votre mot de passe</label>
                    <input type="password" required class="form-control" id="texte" rows="3" name="mdp" placeholder="Entrer ici votre mot de passe">
                </div>
                <button type="submit" class="btn btn-primary" name="submit" value="bouton">Inscription</button>
            </form>
        </div>
    </div>


</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.slim.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>