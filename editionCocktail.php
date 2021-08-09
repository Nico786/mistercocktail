<?php

require 'lib/database.php';
require 'models/cocktail.php';
session_start();


$msg = '';
//UPDATE
if (
    isset($_POST['nom']) &&
    isset($_POST['description']) &&
    isset($_POST['prixMoyen']) &&
    isset($_POST['anneeConception']) &&
    isset($_POST['famille']) &&
    isset($_POST['id']) //indices = name dans phtml. 
) {
    //controle prix moyen
    $erreur = false;


    $_POST['prixMoyen'] = str_replace(',', '.', $_POST['prixMoyen']);
    if (!is_numeric($_POST['prixMoyen'])) {
        $msg .= '<div class="alert alert-danger mb-3">Erreur de saisie au niveau du prix</div>';
        $erreur = true;
    }

    //controle année
    if (!is_numeric($_POST['anneeConception']) || strlen($_POST['anneeConception']) != 4) {
        $msg .= '<div class="alert alert-danger mb-3">La date doit contenir 4 chiffres !</div>';
        $erreur = true;
    }


    //controle sur le nom
    if (empty($_POST['nom'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, le nom du cocktail est obligatoire !</div>';
        $erreur = true;
    }


    $anneeConception = $_POST['anneeConception'] . '-01-01'; //on veut garder le format de date donc on ajoute 01-01

    if (!$erreur) { // if($erreur == false) si pas d'erreur, on enregistre les infos et on redirige vers page
        modifierCocktail($_POST['id'], $_POST['nom'], $_POST['description'], $_POST['prixMoyen'], $anneeConception, $_POST['famille']); //même ordre de parametres que qd on créé la fonction dans cocktail.php 
        //on place un message dans la session afin de pouvoir l'afficher sur la page cible (backoffice.php)

        $_SESSION['message'] ='<div class="alert alert-success">Le cocktail ' . $_POST['nom'] . ' a bien été modifié !</div>';
        header('location:backoffice.php');
        exit();
    }
}

//on vérifie si l'id est présent dans l'url et de type numérique, sinon on redirige:
if (!isset($_GET['id'])) {
    header('location:index.php');
    exit(); //cette fonction prédéfinie  permet de bloquer l'execution du code à la suite
}

$infosCocktail = detailsCocktail($_GET['id']);
// echo '<pre>'; var_dump($infosCocktail); echo '</pre>';
if (!$infosCocktail) {  // si $infosCocktail==false: l'id ne correspond à aucun cocktail, donc on redirige la page
    header('location:index.php');
    exit();
}
//ici, on est sur que l'id existe et que l'on a bien récupéré les infos du cocktails

// On récupère les familles pour l'affichage dans le select
$listeFamille = listerFamille();
//go editionCocktail.phtml pour affichage dans le formulaire





require 'templates/editionCocktail.phtml';
