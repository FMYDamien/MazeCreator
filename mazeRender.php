<?php
/* mazeRender.php
   Génère et affiche un labyrinthe en fonction des paramètres passés dans l'URL.
  
    Paramètres GET :
      - largeur : largeur du labyrinthe
      - hauteur : hauteur du labyrinthe
      - seed : graine aléatoire pour la génération du labyrinthe
      - banque : nom du fichier de tuiles à utiliser
  
    Exemple d'URL : mazeRender.php?largeur=20&hauteur=20&seed=123456&banque=tiles.png
*/

include 'mazeFunction.php';

if (isset($_GET['largeur'], $_GET['hauteur'], $_GET['seed'], $_GET['banque'])) {
    // Récupère les paramètres de génération du labyrinthe
    $largeur = (int)$_GET['largeur'];
    $hauteur = (int)$_GET['hauteur'];
    $seed = (int)$_GET['seed'];
    $banque = $_GET['banque'];

    // Initialise la graine aléatoire
    srand($seed);

    // Génère la structure du labyrinthe
    $maze = initMaze($largeur, $hauteur);
    generateMaze($maze, $largeur, $hauteur);

    // Génère l’image du labyrinthe sans affichage direct
    $image = renderMaze($maze, $banque);

    // Envoie les en-têtes HTTP pour indiquer que le contenu est une image PNG
    header('Content-Type: image/png');
    header('Content-Disposition: inline; filename="labyrinthe.png"');

    // Envoie l'image PNG au navigateur
    imagepng($image);

    // Libère la mémoire
    imagedestroy($image);

    
} else { // Si des paramètres sont manquants 
    echo "Paramètres manquants pour afficher le labyrinthe.";
}
?>
