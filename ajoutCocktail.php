<?php
require 'lib/database.php';
require 'models/cocktail.php';

//Mettre en place un formulaire permettant de créer une nouvelle famille en BDD
//form method post
//champ : nom famille (name="nomFamille")
//champ submit

//via un if demander si l'information existe dans un post
//créer une fonction permettant un insert into dans la table famille
//si vous rentrez dans le if, déclencher appel de fonction afin de créer la nouvelle famille en BDD


$msgFamille = '';

if (isset($_POST['nomFamille'])) {
    //avant l'enregistrement, on vérifie si la famille existe déjà en BDD
    //requete SELECT, si on obtient une ligne cette famille existe sinon on l'enregistre
    $_POST['nomFamille'] = trim($_POST['nomFamille']);
    $familleExiste = verifFamilleExiste($_POST['nomFamille']);
    //echo '<pre>; var_dump($familleExiste); echo '</pre>';
    if ($familleExiste > 0) { //<=> existe dans le tableau
        $msgFamille .= '<div class="alert alert-danger mb-3">Attention, la famille ' . $_POST['nomFamille'] . ' existe déjà.</div>';
    } else {
        ajouterFamille($_POST['nomFamille']);
        $msgFamille .= '<div class="alert alert-success mb-3">La famille ' . $_POST['nomFamille'] . ' a bien été ajoutée !</div>';
    }
}


$msgIngredient = '';

if (isset($_POST['nomIngredient'])) {
    $_POST['nomIngredient'] = trim($_POST['nomIngredient']);
    $ingredientExiste = verifIngredientExiste($_POST['nomIngredient']);
    if ($ingredientExiste > 0) {
        $msgIngredient .= '<div class="alert alert-danger mb-3">Attention, l\'ingrédient ' . $_POST['nomIngredient'] . ' a déjà été rajouté."</div>';
    } else {
        ajouterIngredient($_POST['nomIngredient']);
        $msgIngredient .= '<div class="alert alert-success mb-3">Vous avez rajouté ' . $_POST['nomIngredient'] . ' à la liste.</div>';
    }
}





$nomFamille = listerFamille();

//echo '<pre>'; print_r($_POST); echo '</pre>';
//echo '<pre>'; print_r($_FILES); echo '</pre>';

$msg = ''; //variable destinée a afficher dans message utilisateur (voir dans .phtml)

//Est-ce-que le formulaire à été validé :
if (
    isset($_POST['nom']) &&
    isset($_POST['description']) &&
    isset($_POST['prixMoyen']) &&
    isset($_POST['idFamille']) &&
    isset($_POST['anneeConception'])
) {

    //on applique un trim() sur toutes les valeurs dans $_POST
    foreach ($_POST as $indice => $valeur) {
        $_POST[$indice] = trim($valeur);
    }

    //variable de controle pour tester dans un deuxième temps pour savoir si on peut lancer l'enregistrement
    $erreur = false; //erreur = non, pas d'erreur

    if (empty($_POST['idFamille'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, il est obligatoire de choisir une famille</div>';
        //cas d'erreur, donc:
        $erreur = true;
    }

    //2 controles à mettre en place:  
    // -Prix moyen doit etre numérique ! sinon message d'erreur et on bloque l'ajout
    //option: gestion de la virgule (str_replace)
    // l'année conception doit etre numérique et doit avoir 4 chiffres!
    $_POST['prixMoyen'] = str_replace(',', '.', $_POST['prixMoyen']);

    if (!is_numeric($_POST['prixMoyen'])) {
        $msg .= '<div class="alert alert-danger mb-3">Erreur de saisie au niveau du prix</div>';
        $erreur = true;
    }

    if (!is_numeric($_POST['anneeConception']) || strlen($_POST['anneeConception']) != 4) {
        $msg .= '<div class="alert alert-danger mb-3">La date doit contenir 4 chiffres !</div>';
        $erreur = true;
    }


    //controle sur le nom
    if (empty($_POST['nom'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, le nom du cocktail est obligatoire !</div>';
        $erreur = true;
    }





    //----------- 1ere partie gestion de l'image ------------
    $urlPhoto = ''; //on créé une variable vide pour la requête SQL, et si la photo a été chargée, on la placera dans cette variable vide
    //Pouir les pièces jointes (input type="file"), on les retrouvera dans la superglobale $_FILES
    //$_FILES est un tableau array multidimensionnel
    /*
    Array
    {
    [urlPhoto] => Array
        {
            [name] =>
            [type] =>
            [tmp_name] =>
            [error] => 4
            [size] => 0
        }
    }
    */
    //on vérifie siune photo à bien été chargée
    //if($_FILES['urlPhoto']['error'] == UPLOAD_ERR_OK){}

    if (!empty($_FILES['urlPhoto']['name'])) { //=est ce que name au niveau de urlphoto dans $_files n'est pas vide
        //on récupère l'extension de la photo pour pouvoir controler le format
        //strrchr() // fonction prédéfinie permettant de découper une chaine en partant de la fin selon un caractère fourni en argument
        //nous demandons de découper le nom de l'image jusqu'au . 
        $extension = strrchr($_FILES['urlPhoto']['name'], '.');
        //echo '<pre>'; print_r($extension); echo '</pre>';
        //exemple: on charge une image truc.png => on récupère .png
        //on enlève le point
        $extension = substr($extension, 1); //substr() permet de découper une chaine. On enleve le point
        //substr(la_chaine_a_couper, position_depart);
        //echo '<pre>'; print_r($extension); echo '</pre>';

        //on force la chaine a être en minuscule:strtolower()
        $extension  = strtolower($extension);

        //on créé un tableau array contenant les extensions acceptées
        $extensionValide = array('jpg', 'jpeg', 'webp', 'png', 'gif');

        //in_array() permet de vérifier si le premier argument fait partie d'une des valeurs d'un tableau fourni en deuxième argument (true/false)
        if (in_array($extension, $extensionValide)) {
            //extension OK, on peut enregistrer l'image
            //on renomme le nom de l'image car si on enregistre une image du même nom qu'une autre déjà présente, l'ancienne serait écrasé par la nouvelle.
            //uniqid() fonction prédéfinie nous renvoyant un chiffre basé sur les microsecondes.
            $urlPhoto = uniqId() . $_FILES['urlPhoto']['name'];
            //echo '<pre>'; print_r($urlPhoto) ; echo '</pre>';

            //Pour l'enregistrement de l'image sur le serveur, nous avons besoin du chemin racine serveur
            // __DIR__ est une constante magique nous renvoyant le chemin racine serveur jusqu'au dossier contenant ce fichier
            $destination = __DIR__ . '/assets/images/cocktails/' . $urlPhoto;

            //move_uploaded_file() permet d'enregistrer un fichier depuis un emplacement (1er argument) vers un autre (2e argument)
            move_uploaded_file($_FILES['urlPhoto']['tmp_name'], $destination); // tmp_name =emplacement temporaire du fichier pendant le chargement après validation du formulaire
        } else {
            $msg .= '<div class="alert alert-danger mb-3 text-center">Attention, l\'extension n\'est pas valide.<br>Extensions autorisées: .jpg/ .jpeg/ .webp/ .png/ .gif</div>';
            //cas d'erreur, donc:
            $erreur = true;
        } //FIN VERIF EXTENSION

    } //FIN PHOTO CHARGEE

    // -------2e partie récup des enregistrement pour l'enregistrement-----------

    //VERIF SI YA PAS D'ERREUR
    if ($erreur == false) {
        //on rajoute mois et jour sur l'année car champ date en BDD
        $anneeConception = $_POST['anneeConception'] . '-01-01';

        ajouterCocktails($_POST['nom'], $_POST['description'], $urlPhoto, $anneeConception, $_POST['prixMoyen'], $_POST['idFamille']);
    }
} //FIN DES ISSET POST

//Récupération des cocktails et des ingrédients pour le formulaire affecter un ingrédient
$listeCocktails=listerCocktails(); //on utilise la même que dans backoffice.php
$listeIngredients = listerIngredients();

//Enregistrement relation cocktail/ingrédient
$msgRelation = "";

if(isset($_POST['idCocktail']) && isset($_POST['idIngredient'])){
    $_POST['idCocktail'] = trim($_POST['idCocktail']); //trim ici meme si on est dans le select, pour sécurité au niveau de l'inspecteur
    $_POST['idIngredient'] = trim($_POST['idIngredient']);

    $verifRelation = verifRelationCocktailIngredient($_POST['idIngredient'],$_POST['idCocktail']);
    if($verifRelation > 0){
        $msgRelation .= '<div class="alert alert-danger mb-3">Attention, cette relation existe déjà en base de données!</div>';
    } else {
        ajouterRelationCocktailIngredient($_POST['idIngredient'], $_POST['idCocktail']);
        $msgRelation .= '<div class="alert alert-success mb-3">La relation a bien été ajoutée!</div>';
    }
}


include 'templates/ajoutCocktail.phtml';
