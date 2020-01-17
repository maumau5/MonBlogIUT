<?php
//session_start();

require_once 'config/init.conf.php';
require_once 'include/fonctions.inc.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.conf.php';
include_once 'include/header.inc.php';

if (isset($_GET['recherche'])) {
    /* @var $bdd PDO */

//print_r2($_SESSION);
//print_r2($_GET);
    //echo $_GET['recherche'];

    $page_courante = !empty($_GET['p']) ? $_GET['p'] : 1;

    //$search = $_GET['recherche'];
    $nb_total_articles = countArticles($bdd);
//var_dump($nb_total_articles);

    $index_depart = returnIndex($page_courante, _nb_articles_par_page_);
//var_dump($index_depart);

    $nb_total_pages = ceil($nb_total_articles / _nb_articles_par_page_);
//var_dump($nb_total_pages);

    $select_articles = "SELECT "
            . "id, "
            . "titre, "
            . "texte, "
            . "DATE_FORMAT(date, '%d/%m/%Y') AS date_fr, "
            . "publie "
            . "FROM article "
            . "WHERE publie = :publie "
            . "AND (titre LIKE :recherche OR texte LIKE :recherche) "
            . "LIMIT :index_depart, :nb_articles_par_page";

    $sth = $bdd->prepare($select_articles);
    $sth->bindValue(":publie", 1, PDO::PARAM_BOOL); //fonction pdo qui permet de sécuriser le paramètre avant d'exécuter la requête
    $sth->bindValue(":index_depart", $index_depart, PDO::PARAM_INT);
    $sth->bindValue(":nb_articles_par_page", _nb_articles_par_page_, PDO::PARAM_INT);
    $sth->bindValue(":recherche", '%' . $_GET['recherche'] . '%', PDO::PARAM_STR);
    $sth->execute(); //on exécute la requête préparée juste avant

    $tab_result = $sth->fetchAll(PDO::FETCH_ASSOC);

//print_r2($tab_result);
}
?>

<?php if (!empty($_GET['recherche'])) { ?>

    <?php
    foreach ($tab_result as $cle => $valeur) {
        ?>
        <div class="col-6">
            <div class="card" style="width: 100%;">
                <img src="img/<?= $valeur['id']; ?>.jpg" class="card-img-top" alt="<?= $valeur['titre']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $valeur['titre'] ?></h5>
                    <p class="card-text"><?= $valeur['texte'] ?></p>
                    <a href="#" class="btn btn-primary"><?= $valeur['date_fr'] ?></a>
                    <a href="article.php?id=<?= $valeur['id'] ?>&action=modifier" class="btn btn-primary">Modifier</a>
                </div>
            </div>
        </div>
    <?php 
    
    } 
    
}?>