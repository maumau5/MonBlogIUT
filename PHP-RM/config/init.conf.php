<?php

session_start(); //on démarre la session

//affichage des erreurs et avertissemtns PHP
error_reporting(E_ALL); //Fixe le niveau de rapport d'erreurs PHP
ini_set("display_errors",1);//Change la valeur de l'option de configuration varname 
//et lui donne celle de newvalue. La valeur de l'option de configuration sera modifiée durant toute l'exécution du script et pour ce script spécifiquement. 
//Elle reprendra sa valeur par défaut dès la fin du script. 

define('_nb_articles_par_page_', 2); //on définit une constante _nb_articles_par_pages égale à 2

date_default_timezone_set('Europe/Paris');//pour définir le fuseau horaire utilisé dans le projet (pour les dates)
