<?php
/* mazeFunction.php
  - Fonctions pour générer et afficher un labyrinthe avec des tuiles.
*/


//--------------------- Génération du labyrinthe ---------------------

/**
 * Initialise un labyrinthe de taille $largeur x $hauteur.
 * Chaque case du labyrinthe est initialisée avec les murs Nord, Est, Sud et Ouest fermés.
 * Chaque case a un identifiant de composante unique au début. Son identifiant unique est sa position dans le tableau.
 * 
 * @param int $largeur : largeur du labyrinthe
 * @param int $hauteur : hauteur du labyrinthe
 * @return array : labyrinthe initialisé
 */
function initMaze($largeur, $hauteur) {
    $maze = [];
    $composante = 0;

    for ($y = 0; $y < $hauteur; $y++) {
        for ($x = 0; $x < $largeur; $x++) {
            $maze[$y][$x] = [
                'N' => true,
                'E' => true, 
                'S' => true, 
                'O' => true,  
                'composante' => $composante++
            ];
        }
    }
    return $maze;
}


/**
 * Génère un labyrinthe parfait en utilisant l'algorithme de Fusion Aléatoire.
 * 
 * @param array &$maze : labyrinthe initialisé (passé par référence)
 * @param int $largeur : largeur du labyrinthe
 * @param int $hauteur : hauteur du labyrinthe
 * @return void : le labyrinthe est modifié directement
 */
function generateMaze(&$maze, $largeur, $hauteur) {
    $nbCases = $largeur * $hauteur;
    $mursOuverts = 0;

    while ($mursOuverts < $nbCases - 1) {
        $x = rand(0, $largeur - 1);
        $y = rand(0, $hauteur - 1);
        $direction = ['N', 'E', 'S', 'O'][rand(0, 3)];

        if (peutOuvrir($maze, $x, $y, $direction, $largeur, $hauteur)) {
            ouvrir($maze, $x, $y, $direction);
            $mursOuverts++;
        }
    }
}


/**
 * Vérifie si un mur peut être ouvert dans une direction donnée.
 * 
 * @param array $maze : labyrinthe
 * @param int $x : coordonnée x de la case
 * @param int $y : coordonnée y de la case
 * @param string $direction : direction du mur à ouvrir (N, E, S, O)
 * @param int $largeur : largeur du labyrinthe
 * @param int $hauteur : hauteur du labyrinthe
 * @return bool : true si le mur peut être ouvert, false sinon
 */
function peutOuvrir($maze, $x, $y, $direction, $largeur, $hauteur) {
    switch ($direction) {
        case 'N':
            return $y > 0 && $maze[$y][$x]['composante'] != $maze[$y-1][$x]['composante'];
        case 'E':
            return $x < $largeur - 1 && $maze[$y][$x]['composante'] != $maze[$y][$x+1]['composante'];
        case 'S':
            return $y < $hauteur - 1 && $maze[$y][$x]['composante'] != $maze[$y+1][$x]['composante'];
        case 'O':
            return $x > 0 && $maze[$y][$x]['composante'] != $maze[$y][$x-1]['composante'];
    }
    return false;
}


/**
 * Ouvre un mur entre deux cases et fusionne leurs composantes.
 * 
 * @param array &$maze : labyrinthe (passé par référence)
 * @param int $x : coordonnée x de la case
 * @param int $y : coordonnée y de la case
 * @param string $direction : direction du mur à ouvrir (N, E, S, O)
 * @return void : le labyrinthe est modifié directement
 */
function ouvrir(&$maze, $x, $y, $direction) {
    switch ($direction) {
        case 'N':
            $maze[$y][$x]['N'] = false;
            $maze[$y-1][$x]['S'] = false;
            fusionner($maze, $x, $y, $x, $y-1);
            break;
        case 'E':
            $maze[$y][$x]['E'] = false;
            $maze[$y][$x+1]['O'] = false;
            fusionner($maze, $x, $y, $x+1, $y);
            break;
        case 'S':
            $maze[$y][$x]['S'] = false;
            $maze[$y+1][$x]['N'] = false;
            fusionner($maze, $x, $y, $x, $y+1);
            break;
        case 'O':
            $maze[$y][$x]['O'] = false;
            $maze[$y][$x-1]['E'] = false;
            fusionner($maze, $x, $y, $x-1, $y);
            break;
    }
}


/**
 * Fusionne les composantes de deux cases.
 * 
 * @param array &$maze : labyrinthe (passé par référence)
 * @param int $x1 : coordonnée x de la première case
 * @param int $y1 : coordonnée y de la première case
 * @param int $x2 : coordonnée x de la deuxième case
 * @param int $y2 : coordonnée y de la deuxième case
 * @return void : le labyrinthe est modifié directement
 */
function fusionner(&$maze, $x1, $y1, $x2, $y2) {
    $oldComp = $maze[$y2][$x2]['composante'];
    $newComp = $maze[$y1][$x1]['composante'];
    
    foreach ($maze as $y => $ligne) {
        foreach ($ligne as $x => $case) {
            if ($case['composante'] == $oldComp) {
                $maze[$y][$x]['composante'] = $newComp;
            }
        }
    }
}



//--------------------- Affichage du labyrinthe ---------------------

/**
 * Charge une banque de tuiles à partir d'un fichier PNG.
 * 
 * @param string $nomBanque : nom du fichier de tuiles (sans l'extension)
 * @return : image de la banque de tuiles
 */
function chargerBanque($nomBanque) {
    $cheminBanque = "img/" . $nomBanque . ".png";
    return imagecreatefrompng($cheminBanque);
}


/**
 * Récupère la tuile et la rotation correspondantes pour une case donnée.
 * 
 * @param array $maze : labyrinthe
 * @param int $x : coordonnée x de la case
 * @param int $y : coordonnée y de la case
 * @return array : [index de la tuile, rotation en degrés]
 */
function trouverTuile($maze, $x, $y) {
    $case = $maze[$y][$x];
    
    $nord = !$case['N'];
    $est = !$case['E'];
    $sud = !$case['S'];
    $ouest = !$case['O'];

    // ------ Cas sans rotation (de base sur la banque de tuiles)
    if ($nord && $est && $sud && $ouest) {   // Tous les côtés ouverts
        return [0, 0];

    } elseif ($sud && $ouest && !$nord && !$est) {  // Ouest et Sud ouverts
        return [1, 0];

    } elseif ($sud && !$nord && !$ouest && !$est) {  // Sud ouvert
        return [2, 0];

    } elseif ($nord && $sud && !$est && $ouest) {  // Ouest, Nord, et Sud ouverts
        return [3, 0];

    } elseif ($nord && $sud && !$ouest && !$est) {  // Nord et Sud ouverts
        return [4, 0];
    } 
    
    // ------ Cas avec des rotations 
    elseif ($est && $sud && !$nord && !$ouest) {  // Est et Sud ouverts
        return [1, 90];

    } elseif ($nord && $est && !$sud && !$ouest) {  // Nord et Est ouverts
        return [1, 180];

    } elseif ($nord && $ouest && !$sud && !$est) {   // Nord et Ouest ouverts
        return [1, 270];

    } elseif ($est && !$nord && !$sud && !$ouest) {  // Est ouvert
        return [2, 90];

    } elseif ($nord && !$sud && !$est && !$ouest) {   // Nord ouvert
        return [2, 180];

    } elseif ($ouest && !$nord && !$sud && !$est) {   // Ouest ouvert
        return [2, 270];

    }  elseif (!$nord && $sud && $est && $ouest) {  // Sud, Est et ouest ouverts
        return [3, 90];

    } elseif ($nord && $sud && $est && !$ouest) {   // Nord, Sud et Est ouverts
        return [3, 180];

    } elseif ($nord && $est && $ouest && !$sud) {   // Nord, Est et Ouest ouverts
        return [3, 270];

    } elseif ($est && $ouest && !$nord && !$sud) {   // Est et Ouest ouverts
        return [4, 90];
    
    // Par défaut : renvoyer une tuile entièrement ouverte
    } return [0, 0]; // Normalement inutile mais on sait jamais
}


/**
 * Génère une image du labyrinthe en utilisant une banque de tuiles.
 * 
 * @param array $maze : labyrinthe
 * @param string $banque : nom du fichier de tuiles
 * @param int $tailleCase : taille des tuiles (par défaut 64x64)
 * @return : image du labyrinthe
 */
function renderMaze($maze, $banque, $tailleCase = 64) {
    $largeur = count($maze[0]) * $tailleCase;
    $hauteur = count($maze) * $tailleCase;

    // Crée une image pour le labyrinthe
    $image = imagecreatetruecolor($largeur, $hauteur);

    // Charge la banque de tuiles (320x64)
    $banqueImage = chargerBanque($banque);

    // La taille de base des tuiles dans la banque est de 64x64 pixels
    $tailleCaseOriginale = 64;

    foreach ($maze as $y => $ligne) {
        foreach ($ligne as $x => $case) {
            // Trouve la tuile correspondante et sa rotation puis affecte les résultats à des variables distinctes avec la fonction list()
            list($indexTuile, $rotation) = trouverTuile($maze, $x, $y);
            
            // Calcule la position de la tuile dans l'image de la banque
            $srcX = $indexTuile * $tailleCaseOriginale;
            $srcY = 0;  // Toutes les tuiles sont sur la même ligne

            // Extraire la tuile à partir de l'image de la banque
            $tuile = imagecreatetruecolor($tailleCaseOriginale, $tailleCaseOriginale);
            imagecopy($tuile, $banqueImage, 0, 0, $srcX, $srcY, $tailleCaseOriginale, $tailleCaseOriginale);

            // Applique la rotation si nécessaire
            if ($rotation != 0) {
                $tuile = imagerotate($tuile, $rotation, 0);
            }

            // Redimensionne la tuile si la taille du labyrinthe final est différente
            $resizedTuile = imagecreatetruecolor($tailleCase, $tailleCase);
            imagecopyresampled($resizedTuile, $tuile, 0, 0, 0, 0, $tailleCase, $tailleCase, $tailleCaseOriginale, $tailleCaseOriginale);

            // Copie la tuile redimensionnée dans l'image finale du labyrinthe
            imagecopy($image, $resizedTuile, $x * $tailleCase, $y * $tailleCase, 0, 0, $tailleCase, $tailleCase);

            // Libére la mémoire
            imagedestroy($resizedTuile);
            imagedestroy($tuile);
        }
    }

    return $image;
}

?>
