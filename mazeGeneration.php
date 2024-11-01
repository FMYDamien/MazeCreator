<?php 
/* Page pour exporter l'image du labyrinthe généré
 
    - Affiche l'image du labyrinthe généré
    - Permet de télécharger l'image du labyrinthe généré
    - Permet de revenir à la page de création de labyrinthe
*/

include 'mazeFunction.php'; // Inclut les fonctions pour générer le labyrinthe


// Vérifie que tous les paramètres sont présents
if (isset($_GET['largeur']) && isset($_GET['hauteur']) && isset($_GET['banque'])) {
    // Récupère les paramètres
    $largeur = (int)$_GET['largeur'];
    $hauteur = (int)$_GET['hauteur'];
    $banque = $_GET['banque'];

    // Initialiser la graine aléatoire
    if (isset($_GET['seed']) && $_GET['seed'] !== '') {  // Si le champs n'est pas rempli, il vaut '' par défaut, mais on couvre le cas possibles où il est null ou pas définie au cas où
        $seed = (int)$_GET['seed'];                     // Après quelques recherches, il existe également empty() pour vérifier si un champ est défini mais vide. 
        srand($seed);                                   // Cependant, empty() considère comme "vide" non seulement une chaîne vide, mais aussi des valeurs comme 0, false, ou null
    } else {
        $seed = mt_rand(); // Génère une graine aléatoire si aucune n'est fournie. Nous n'utilisons pas juste srand() car nous avons besoin de connaître la graine pour la sauvegarder dans l'URL
        srand($seed); 
    }
    
    // Génère l'URL pour afficher l'image du labyrinthe
    $imageURL = "mazeRender.php?largeur=$largeur&hauteur=$hauteur&seed=$seed&banque=$banque"; // Chaque labyrinthe peut être appelé par son URL grâce à mazeRender.php, ce qui permet de le sauvegarder pour plus tard
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MazeExport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"> <!-- Importation de la police, voir : https://www.oboqo.com/comment-importer-une-police-depuis-google-fonts/ -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="design.css"> <!-- bug xampp avec style, voir mazeCreator.html-->
</head>
<body>
    <h1>MazeExport</h1>

    <div class="card">
    <!-- Affichage de l'image du labyrinthe -->
    <p>Labyrinthe généré :</p>
    <img src="<?php echo $imageURL; ?>" alt="Aperçu du labyrinthe généré">

    <!-- Formulaire pour télécharger l'image -->
    <form method="POST" action="mazeExport.php">
        <input type="hidden" name="largeur" value="<?php echo $largeur; ?>">
        <input type="hidden" name="hauteur" value="<?php echo $hauteur; ?>">
        <input type="hidden" name="seed" value="<?php echo $seed; ?>">
        <input type="hidden" name="banque" value="<?php echo $banque; ?>">

        <!-- Choix du format -->
        <label for="format">Format de l'image :</label>
        <select name="format" id="format" required>
            <option value="png">PNG</option>
            <option value="jpeg">JPEG</option>
        </select><br><br>

        <!-- Choix de la taille -->
        <!-- Nous avons choisis d'imposer les différentes tailles pour ne pas avoir à gérer la proportion des images -->
        <label for="size">Taille de l'image :</label>
        <select name="size" id="size" required>
            <option value="0.25">1/4 de la taille</option>
            <option value="0.5">1/2 de la taille</option>
            <option value="1" selected>Taille originale</option>
            <option value="2">2x la taille</option>
            <option value="4">4x la taille</option>
        </select><br><br>

        <input class="button" type="submit" value="Télécharger l'image">
    </form>
    </div>


    <!-- Bouton pour revenir à la page de création -->
    <form method="GET" action="mazeCreator.html">
        <input class="button bottom" type="submit" value=" ⭠ Retour à la création de labyrinthe">
    </form>
</body>
</html>

<?php
// Si les paramètres ne sont pas présents, on affiche un message d'erreur
} else {
    echo "Paramètres manquants pour générer le labyrinthe.";
}
?>