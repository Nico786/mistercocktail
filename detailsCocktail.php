<?php
require 'lib/database.php';
require 'models/cocktail.php';
session_start();

/* 
1 -créer une vue (phtml) et un controleur (php) pour la page detailsCocktail
2 -depuis index.phtml: mettre en place des liens pour accéder à la page détails
3 -sur cette page, on a besoin de l'id du cocktail que l'on doit afficher
       -si l'id n'est pas présent, on redirige vers index
4-recupération des infos du cocktail en BDD detailsCocktail()
        -si on ne récupère rien de la BDD, on redirige sur index.php.
5-Faire une structure html pour afficher toutes les infos du cocktail
*/


if(!isset($_GET['id'])){
    header('location:index.php');
    exit();
}

$infosCocktail = detailsCocktail($_GET['id']);
//echo '<pre>'; print_r($infosCocktail) ; echo '</pre>';

if (!$infosCocktail) {  // si $infosCocktail==false: l'id ne correspond à aucun cocktail, donc on redirige la page. 
    header('location:index.php');
    exit();
}


$listeIngredients = detailsIngredients($_GET['id']);














require 'templates/detailsCocktail.phtml';