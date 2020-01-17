<?php
require_once 'config/init.conf.php';
require_once 'include/fonctions.inc.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.conf.php';
include_once 'include/header.inc.php';

/* @var $bdd PDO */

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id_article = $_GET['id'];

    $req = $bdd->prepare('SELECT * FROM article WHERE id = ' .$id_article);
    $req->execute();
    $article = $req->fetch();// Récupère la ligne suivante d'un jeu de résultats PDO 
    //var_dump($article);

    if (!empty(filter_input(INPUT_POST, 'submit'))) { //si on a appuyé sur le bouton de soumission du formulaire alors
//print_r2(filter_input_array(INPUT_POST));
//print_r2(filter_input_array(INPUT_POST));
        $titre_nouveau = $_POST['titre'];
        $texte_nouveau = $_POST['texte'];

        $publie_nouveau = isset($_POST['publie']) ? 1 : 0; //condition ternaire qui dit que si la checkbox du publié est coché, alors publie prend la valeur 1, sinon il prend la valeur 0

        $date_nouveau = date('Y-m-d'); //on met la date au format américain qui est ANNEE-MOIS-JOUR

        $sth = $bdd->prepare("UPDATE article " //on prépare la requête de modification de l'article dans la base de données
                . "SET titre=:titre_nouveau, texte=:texte_nouveau, publie=:publie_nouveau, date=:date_nouveau "
                . "WHERE id=" . $id_article);
        $sth->bindValue(':titre_nouveau', $titre_nouveau, PDO::PARAM_STR);
        $sth->bindValue(':texte_nouveau', $texte_nouveau, PDO::PARAM_STR);
        $sth->bindValue(':publie_nouveau', $publie_nouveau, PDO::PARAM_BOOL);
        $sth->bindValue(':date_nouveau', $date_nouveau, PDO::PARAM_STR);

        $sth->execute(); //on exécute la requête préparée au préalable (retourne true si tout s'est bien passé, sinon erreur)
//$id_article = $bdd->lastInsertId(); //on récupère et on donne à la variable $id_article le dernier id inseré dans la base de données

        if ($_FILES['img']['error'] == 0) {//si l'image a le code 0 au moment de l'upload alors
            move_uploaded_file($_FILES['img']['tmp_name'], 'img/' . $id_article . '.jpg'); //on récupère l'image uploadée avec son nom temporaire et on la met dans le répertoire img du site avec comme nom son id et comme extension '.jpg'
        }

        $message = '<b>Félicitation</b>, votre article a été modifié avec succès !'; //message de notification affiché sur la page d'accueil lorsqu'un article s'est bien ajouté à la base de données
        $result = 'success'; //pour savoir si l'envoi de l'article s'est bien passé ou pas

        declareNotification($message, $result);

        header("Location: index.php"); //on redirige l'utilsateur à la page d'accueil lorsqu'il a inséré un article.

        exit(); //fin de l'exécution du script
    }
    ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Modifier un article</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form method="POST" action="article.php?action=modifier&id=<?= $_GET['id']; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php= ['id']; ?>">
                    <input type="hidden" name="action" value="<?php= ['action']; ?>">
                    <div class="form-group">
                        <label for="titre">Le titre</label>
                        <input type="text" class="form-control" id="titre" name="titre" value="<?php print $article['titre']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="texte">Le contenu de l'article</label>
                        <input type="textarea" class="form-control" id="texte" rows="3" name="texte" placeholder="Entrer ici le contenu de l'article" value="<?php print $article['texte']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="img">L'image de mon article</label>
                        <input type="file" class="form-control-file" id="img" name="img"">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="publie" name="publie" value=""<?php if($article['publie'] == 1){ ?>checked <?php } ?>>
                        <label class="form-check-label" for="publie">Article publié ?</label>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit" value="bouton">Modifier mon article</button>
                </form>
            </div>
        </div>
    </div>

    <?php
} else {
    if (!empty(filter_input(INPUT_POST, 'submit'))) { //si on a appuyé sur le bouton de soumission du formulaire alors
        //print_r2(filter_input_array(INPUT_POST));
        //print_r2(filter_input_array(INPUT_POST));
        $titre = filter_input(INPUT_POST, 'titre');
        $texte = filter_input(INPUT_POST, 'texte');

        $publie = isset($_POST['publie']) ? 1 : 0; //condition ternaire qui dit que si la checkbox du publié est coché, alors publie prend la valeur 1, sinon il prend la valeur 0

        $date = date('Y-m-d'); //on met la date au format américain qui est ANNEE-MOIS-JOUR
        //echo $date;

        $sth = $bdd->prepare("INSERT INTO article " //on prépare la requête d'insertion de l'article dans la base de données
                . "(titre, texte, publie, date) "
                . "VALUES (:titre, :texte, :publie, :date)");
        $sth->bindValue(':titre', $titre, PDO::PARAM_STR);
        $sth->bindValue(':texte', $texte, PDO::PARAM_STR);
        $sth->bindValue(':publie', $publie, PDO::PARAM_BOOL);
        $sth->bindValue(':date', $date, PDO::PARAM_STR);

        $sth->execute(); //on exécute la requête préparée au préalable (retourne true si tout s'est bien passé, sinon erreur)

        $id_article = $bdd->lastInsertId(); //on récupère et on donne à la variable $id_article le dernier id inseré dans la base de données

        if ($_FILES['img']['error'] == 0) {//si l'image a le code 0 au moment de l'upload alors
            move_uploaded_file($_FILES['img']['tmp_name'], 'img/' . $id_article . '.jpg'); //on récupère l'image uploadée avec son nom temporaire et on la met dans le répertoire img du site avec comme nom son id et comme extension '.jpg'
        }

        $message = '<b>Félicitation</b>, votre article est ajouté !'; //message de notification affiché sur la page d'accueil lorsqu'un article s'est bien ajouté à la base de données
        $result = 'success'; //pour savoir si l'envoi de l'article s'est bien passé ou pas

        declareNotification($message, $result);

        header("Location: index.php"); //on redirige l'utilsateur à la page d'accueil lorsqu'il a inséré un article.

        exit(); //fin de l'exécution du script
    }
    ?>
    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Ajouter un article</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form method="POST" action="article.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titre">Le titre</label>
                        <input type="text" class="form-control" id="titre" name="titre">
                    </div>
                    <div class="form-group">
                        <label for="texte">Le contenu de l'article</label>
                        <input type="textarea" class="form-control" id="texte" rows="3" name="texte" placeholder="Entrer ici le contenu de l'article">
                    </div>
                    <div class="form-group">
                        <label for="img">L'image de mon article</label>
                        <input type="file" class="form-control-file" id="img" name="img">
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="publie" name="publie">
                        <label class="form-check-label" for="publie">Article publié ?</label>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit" value="bouton">Soumettre mon article</button>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.slim.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>