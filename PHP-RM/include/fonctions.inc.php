<?php

function print_r2($ma_variable) {
    echo '<pre>';
    print_r($ma_variable);//Affiche des informations lisibles pour une variable
    echo '</pre>';
    
    return true;
}

function declareNotification($message, $result) {
    $_SESSION['notification']['message'] = $message;
    $_SESSION['notification']['result'] = $result;
    
    return true;
}

function countArticles($bdd){
    /* @var $bdd PDO */
    $sth = $bdd->prepare("SELECT COUNT(*) as total "
            . "FROM article "
            . "WHERE publie = :publie");
    $sth->bindValue(':publie', 1, PDO::PARAM_BOOL);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'];
}

function returnIndex($page_courante, $nb_articles_par_page){
    $index_depart = ($page_courante -1) * $nb_articles_par_page;
    
    return $index_depart;
}