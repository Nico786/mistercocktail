<?php

//index.php : appel de tous les cocktails avec le nom de leur famille
function listerCocktails()
{

    //connexion a la BDD
    $pdo = connectToDatabase();

    //préparation de la requête
    $query = $pdo->prepare("
    SELECT Cocktail.id, nom, nomFamille, description, urlPhoto, YEAR(dateConception) AS anneeConception, prixMoyen
    FROM Cocktail
    INNER JOIN Famille ON idFamille = Famille.id;
    ");

    //on exécute
    $query->execute();

    //on renvoie les données; plusieurs lignes car renvoie tous les cocktails avec leur nom
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

//index.php : appel de tous les cocktails selon le nom de leur famille
function listerCocktailsSelonFamille($nomFamille)
{

    //connexion a la BDD
    $pdo = connectToDatabase();

    //préparation de la requête
    $query = $pdo->prepare("
    SELECT Cocktail.id, nom, nomFamille, description, urlPhoto, YEAR(dateConception) AS anneeConception, prixMoyen
    FROM Cocktail
    INNER JOIN Famille ON idFamille = Famille.id
    WHERE nomFamille =?
    ");

    //on exécute
    $query->execute([$nomFamille]);

    //on renvoie les données. Plusieurs lignes car plusieurs cocktail par famille
    return $query->fetchAll(PDO::FETCH_ASSOC);
}



// Récupération des familles ayant des cocktails affectés ainsi que le nombre de cocktails par famille
function listerFamilleEtNombre()
{
    $pdo = connectToDatabase();


    $query = $pdo->prepare("
SELECT nomFamille, COUNT(idFamille) AS nombre
FROM cocktail
INNER JOIN famille ON idFamille = famille.id
GROUP BY nomFamille;
");

    $query->execute();

    return $query->fetchALl(PDO::FETCH_ASSOC); //plusieurs lignes car pas de conditions donc : familles + combien
}
//puis go index.php ligne 22

// Faire une requete pour récupérer la liste des famille avec le nombre de cocktail par famille.
// colonnes: nomFamille - nombre
// COUNT() / GROUP BY

/* SELECT nomFamille, COUNT(idFamille) AS nbFamille
FROM cocktail
INNER JOIN famille ON idFamille = famille.id
GROUP BY nomFamille; */

function listerFamille()
{
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT nomFamille, id
    FROM famille
    ORDER by nomFamille
");

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC); //plusieurs lignes car pas de condition donc renvoie toutes les familles
}


//Enregistrement cocktails dans BDD
function ajouterCocktails($nom, $description, $urlPhoto, $anneeConception, $prixMoyen, $idFamille)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT INTO Cocktail
        (
            nom,
            description,
            urlPhoto,
            dateConception,
            prixMoyen,
            idFamille
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?
        )
    ");
    $query->execute(
        [
            $nom,
            $description,
            $urlPhoto,
            $anneeConception,
            $prixMoyen,
            $idFamille
        ]
    );
}


function listerCocktailsBO()
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT cocktail.id, nom, urlPhoto, nomFamille
    FROM cocktail
    INNER JOIN famille ON idFamille = famille.id;
    ");

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC); //pas de conditions, donc renvoie tous les cocktails
} //puis backoffice.php ligne 19


//backoffice.php: suppression d'un cocktail. On veut supprimer en fonction d'un id
function deleteCocktail($id)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    DELETE 
    FROM cocktail
    WHERE id = ?
    ");

    $query->execute([$id]);
} //puis go backoffice.php ligne 7

//editionCocktail.php : récupération des détails d'un cocktail
function detailsCocktail($id)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT cocktail.id, idFamille, nom, nomFamille, description, urlPhoto, YEAR(dateConception) AS anneeConception, prixMoyen
        FROM cocktail
        INNER JOIN famille ON idFamille = famille.id 
        WHERE cocktail.id = ?
    ");

    $query->execute([$id]);

    //une seule ligne car id est unique =>fetch()
    return $query->fetch(PDO::FETCH_ASSOC);
}

//editionCocktail.php: modification d'un cocktail
function modifierCocktail($id, $nom, $description, $prixMoyen, $anneeConception, $idFamille)
{
    $pdo = connectToDatabase();

    $query =  $pdo->prepare("
        UPDATE cocktail SET
            nom = ?,
            description = ?,
            prixMoyen = ?,
            dateConception = ?,
            idFamille = ?
        WHERE id = ?
    ");

    $query->execute([     //meme ordre que les points d'interrogation
        $nom,
        $description,
        $prixMoyen,
        $anneeConception,
        $idFamille,
        $id
    ]);
}


//ajouterCocktail.php: vérification si famille existe
function verifFamilleExiste($famille)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT *
        FROM famille
        WHERE nomFamille = ?
    ");

    $query->execute([$famille]);

    //rowCount() est une méthode présente dans l'objet réponse d'une requête avec PDO (PDOStatement) qui nous renvoie le nombre de ligne dans une réponse
    return $query->rowCount();
}

//ajouterCocktail.php : rajouter une famille
function ajouterFamille($famille)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT 
        INTO Famille (nomFamille)
        VALUES (?)
    ");

    $query->execute([$famille]);
}



//ajouteCocktail.php: est-ce que l'ingrédient existe déjà ?
function verifIngredientExiste($ingredient)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT *
        FROM Ingredients
        WHERE nomIngredient = ?
    ");
    $query->execute([$ingredient]);
    return $query->rowCount();
}

//ajouterCocktail.php : ajouter un ingrédient
function ajouterIngredient($ingredient)
{

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT INTO Ingredients (nomIngredient)
        VALUES (?)
    ");

    $query->execute([$ingredient]);
}


//ajouterCocktail.php: récup des ingrédients:
function listerIngredients()
{
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT * 
        FROM Ingredients 
        ORDER BY nomIngredient
    ");

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

//ajouteCocktail.php: verif relation cocktail/Ingredient
function verifRelationCocktailIngredient($idIngredient, $idCocktail){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT * 
    FROM relationCocktailIngredient 
    WHERE idIngredient = ? AND idCocktail = ?
    ");

    $query->execute([$idIngredient,$idCocktail]);

    return $query -> rowCount();
}

//ajouteCocktail.php: enregistrement relation cocktail/Ingredient
function ajouterRelationCocktailIngredient($idIngredient, $idCocktail){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT INTO relationCocktailIngredient (idIngredient, idCocktail)
        VALUES (? , ?)
    ");

    $query->execute([$idIngredient,$idCocktail]);
    
    //return $query -> rowCount()
}

//detailsCocktail.php: récup des ingrédients
function detailsIngredients($idCocktail){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT nomIngredient
        FROM Ingredients
        INNER JOIN relationCocktailIngredient ON Ingredients.id = idIngredient
        WHERE idCocktail = ?
        ORDER BY nomIngredient
    ");

    $query->execute( [$idCocktail] );
    
    return $query->fetchAll(PDO::FETCH_ASSOC);

}