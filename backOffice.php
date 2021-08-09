<?php


require 'lib/database.php';
require 'models/cocktail.php';
session_start();

//<-ajoutCocktail.phtml
//DELETE
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id']) && is_numeric($_GET['id'])){
    //is_numeric() permet de tester si une valeur est numérique quelque soit son type
    //une valeur dans $_GET et $_POST est toujours de type string
    deleteCocktail($_GET['id']);
}

//Exercice
//Créer une fonction sur Models/cocktail.php nommée listerCocktailsBO()
//Cette fonction doit récupérer la liste de tous les cocktails, champs attendus:

    //- id (table cocktail)
    //- nom 
    //- urlPhoto
    //- nomFamille
// Récupérer la réponse de cette fonction : $listeCocktails
// Afficher ces infos dans le tableau html sur .phtml (l'image doit etre ds un img="src")


$listeCocktails = listerCocktailsBO(); //puis visuel backoffice.phtml
// echo '<pre>'; print_r($listeCocktails); echo '</pre>' pour vérifier et voir ce qu'on récupère

$messageSession = '';
if(!empty($_SESSION['message'])){
    $messageSession = $_SESSION['message'];
    //on récupère le message et on le supprime de la session pour ne pas le réafficher
    //pour supprimer un élément d'un tableau array: unset
    unset($_SESSION['message']);
}

//echo '<pre>'; print_r($_SESSION); echo'</pre>';

require 'templates/backOffice.phtml';