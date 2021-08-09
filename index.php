<?php

//différence entre include et require
//en cas d'erreur, fichier non trouvé: include provoque un warning et continu l'execution du code, require provoque une erreur fatale et bloque l'execution du code
require 'lib/database.php';
require 'models/cocktail.php';
session_start();
// session_start permet de créer un fichier de session côté serveur et un cookie côté utilisateur (navigateur)
// cette fonction doit etre executée AVANT le moindre affichage dans la page html sinon: erreur
// c'est une superglobale donc un tableau array
// nous pouvons placer des informations dedans afin de les appeler à tout moment : 
//$_SESSION
// CHECK PIERRE GIRAUD 


//Rajoutez un if else pour appeler tous les cocktails, ou uniquement ceux d'une famille
//$listeCocktails = listerCocktails( $GET['famille'] );
if (isset($_GET['famille'])) {
    $listeCocktails = listerCocktailsSelonFamille($_GET['famille']);

    if(count($listeCocktails) < 1){ //si on ne récup aucune ligne de la BDD, on affiche tout les cocktails suur la page
        $listeCocktails = listerCocktails();
    }

} else {
    $listeCocktails = listerCocktails();
    //Si il n'a pas cliqué, on affiche tout
}

//echo '<pre>'; print_r($listeCocktails); echo'</pre>';

//on récupère la liste des familles ainsi que le nb de cocktails pour l'affichage dans le bouton déroulant
$listeFamilleEtNombre = listerFamilleEtNombre();
//puis go phtml pour affichage ligne 25


//récupération du contenu de la page via la fonction provenant de Models





include 'templates/index.phtml';
