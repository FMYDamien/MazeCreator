<?php
/* mazeExport.php
  "Génère" et télécharge un labyrinthe en fonction des paramètres passés en POST.
  
    Paramètres POST :
      - largeur : largeur du labyrinthe
      - hauteur : hauteur du labyrinthe
      - seed : graine aléatoire pour la génération du labyrinthe
      - banque : nom du fichier de tuiles à utiliser
      - format : format de l'image à générer (png ou jpeg)
      - size : facteur de redimensionnement de l'image
*/

include 'mazeFunction.php';

if (isset($_POST['largeur'], $_POST['hauteur'], $_POST['seed'], $_POST['banque'], $_POST['format'], $_POST['size'])) {
    // Récupération des paramètres de génération
    $largeur = (int)$_POST['largeur'];
    $hauteur = (int)$_POST['hauteur'];
    $seed = (int)$_POST['seed'];
    $banque = $_POST['banque'];
    $format = $_POST['format'];
    $multiplicateurTaille = (float)$_POST['size'];

    // Applique le facteur de redimensionnement pour obtenir la nouvelle taille
    $newTailleCase = 64 * $multiplicateurTaille;

    // Initialise la graine aléatoire
    srand($seed);

    // Génère le labyrinthe
    $maze = initMaze($largeur, $hauteur);
    generateMaze($maze, $largeur, $hauteur);

    // Génère l'image du labyrinthe avec la taille de tuile redimensionnée
    $imageData = renderMaze($maze, $banque, $newTailleCase);

    // Envoie les en-têtes HTTP pour le téléchargement
    header("Content-Disposition: attachment; filename=labyrinthe.$format");

    // Utilise le format demandé
    if ($format === 'png') {
        header('Content-Type: image/png'); // Envoie les en-têtes HTTP pour indiquer que le contenu est une image PNG
        imagepng($imageData); // Envoie l'image PNG au navigateur
    } elseif ($format === 'jpeg') {
        header('Content-Type: image/jpeg');  // Envoie les en-têtes HTTP pour indiquer que le contenu est une image JPEG
        imagejpeg($imageData);  // Envoie l'image JPEG au navigateur
    }

    // Libère la mémoire
    imagedestroy($imageData);


} else { // Si des paramètres sont manquants
    echo "Paramètres manquants pour télécharger le labyrinthe.";
}
?>
