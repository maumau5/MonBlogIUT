<?php
require_once 'config/init.conf.php';//identique à require mis à part que PHP vérifie si le fichier a déjà été inclus, et si c'est le cas, ne l'inclut pas une deuxième fois. 
require_once 'include/fonctions.inc.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.conf.php';
include_once 'include/header.inc.php';

/* @var $bdd PDO */

//print_r2($_SESSION);

//print_r2($_GET);

$page_courante = !empty($_GET['p']) ? $_GET['p'] : 1; //si p n'est pas vide alors il prend la valeur de $_GET['p'] sinon il prend la valeur 1

$nb_total_articles = countArticles($bdd);
//var_dump($nb_total_articles);

$index_depart = returnIndex($page_courante, _nb_articles_par_page_);
//var_dump($index_depart);

$nb_total_pages = ceil($nb_total_articles / _nb_articles_par_page_); //ceil arrondit au nombre supérieur, floor() fait l'inverse
//var_dump($nb_total_pages);

$sth = $bdd->prepare("SELECT id,"//on prépare la requête à exécuter
        . "titre, "
        . "texte, "
        . "DATE_FORMAT(date, '%d/%m/%Y') AS date_fr, "
        . "publie "
        . "FROM article "
        . "WHERE publie = :publie "
        . "LIMIT :index_depart, :nb_articles_par_page");
$sth->bindValue(":publie", 1, PDO::PARAM_BOOL); //fonction pdo qui permet de sécuriser le paramètre avant d'exécuter la requête
$sth->bindValue(":index_depart", $index_depart, PDO::PARAM_INT);
$sth->bindValue(":nb_articles_par_page", _nb_articles_par_page_, PDO::PARAM_INT);
$sth->execute(); //on exécute la requête préparée juste avant

$tab_result = $sth->fetchAll(PDO::FETCH_ASSOC); //Retourne un tableau contenant toutes les lignes du jeu d'enregistrements + Récupère une ligne de résultat sous forme de tableau associatif

//print_r2($tab_result);
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Articles publiés</h1>
            <p class="lead">Bonne lecture !</p>
            <ul class="list-unstyled">
                <!--<li>Bootstrap 4.3.1</li>
                <li>jQuery 3.4.1</li>-->
            </ul>
        </div>
    </div>
    <?php
    if (isset($_SESSION['notification'])) {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= $_SESSION['notification']['result'] ?> alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $_SESSION['notification']['message'] ?>
                    <?php unset($_SESSION['notification']) ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <?php
        foreach ($tab_result as $key => $value) { //  passe en revue le tableau $tab_result. À chaque itération, la valeur de l'élément courant est assignée à $value et le pointeur interne de tableau est avancé d'un élément (ce qui fait qu'à la prochaine itération, on accédera à l'élément suivant).La seconde forme assignera en plus la clé de l'élément courant à la variable $key à chaque itération. 
            ?>
            <div class="col-6">
                <div class="card" style="width: 100%;">
                    <img class="card-img-top" src="img/<?= $value['id']; ?>.jpg" alt="<?= $value['titre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $value['titre']; ?></h5>
                        <p class="card-text"><?= $value['texte']; ?></p>
                        <a href="#" class="btn btn-primary"><?= $value['date_fr']; ?></a>
                        <?php 
                        if($connecte == true){ ?>
                        <a href="article.php?id=<?= $value['id']; ?>&action=modifier" class="btn btn-primary">Modifier</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

</div>
<div class="row">
    <div class="col-12">
        <nav>
            <ul class="pagination pagination-lg">
                <?php
                        for ($index = 1; $index <= $nb_total_pages; $index++) {
                            $active = $page_courante == $index ? 'active' : '';
                ?>
                <li class="page-item <?= $active ?>"><a class="page-link" href="?p=<?= $index ?>"><?= $index ?> </a></li>
                <?php
                        }
                ?>
            </ul>
        </nav>
    </div>

</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.slim.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
