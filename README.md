# MazeCreator

Ce projet est une application web réalisée dans le cadre d'un DM de SIA (IG2I-LE2). Cette application permet de **générer des labyrinthes parfaits** et d’offrir à l’utilisateur une interface pour configurer, visualiser et télécharger le labyrinthe sous forme d’image. Les images sont générées en utilisant la bibliothèque **GD2**, avec possibilité de sélectionner le format (PNG ou JPEG) et la taille souhaitée.

## Fonctionnalités

- **Génération de labyrinthes parfaits** de taille configurable, basés sur une graine aléatoire ou définie.
- **Affichage des labyrinthes** avec des tuiles prédéfinies, représentant différents types de murs (ouverts/fermés).
- **Téléchargement du labyrinthe** en format PNG ou JPEG, avec options de redimensionnement (taille originale, 2x, 0.5x ...).
- **Interface intuitive** pour la personnalisation du labyrinthe et le choix des options d'exportation.

## Technologies utilisées

- **PHP** : pour le back-end et la logique de génération du labyrinthe.
- **GD2** : bibliothèque PHP pour manipuler et créer les images du labyrinthe.
- **HTML** et **CSS** : pour la structure et le style de l’interface utilisateur.

## Installation

### Prérequis

- **Serveur PHP** avec GD2 activé.
- **Navigateur web recent** pour accéder à l’interface utilisateur de manière correcte *(car le CSS utilise des propriétés récentes)*. 

### Étapes

1. **Cloner le dépôt** ou télécharger les fichiers du projet.
   ```bash
   git clone https://github.com/FMYDamien/MazeCreator.git
   ```
2. **Déplacer les fichiers** dans un répertoire accessible par votre serveur web (ex. : `htdocs` pour XAMPP, ou `www` pour WAMP).

3. **Activer la librairie GD2** si elle n’est pas déjà active. Pour cela, ouvrez le fichier `php.ini` et décommentez la ligne :
   ```ini
   extension=gd2
   ```

4. **Démarrer le serveur** et accéder au projet via l'URL appropriée (par exemple : `http://localhost/MazeCreator`).

## Utilisation

1. **Étape 1 : Configuration du labyrinthe**
   - Ouvrez `mazeCreator.html` pour configurer le labyrinthe.
   - Choisissez la largeur, la hauteur, et si vous le souhaitez, la graine pour le générateur aléatoire.
   (Une graine identique, avec les mêmes paramètres, permet de générer un labyrinte identique. Sinon, la génération est aléatoire.)
   - Sélectionnez la **banque de tuiles** définissant le design du labyrinthe.

2. **Étape 2 : Visualisation et téléchargement**
   - Une fois le labyrinthe généré, il est affiché sur la page suivante avec des options pour le télécharger.
   - Choisissez le **format de téléchargement** (PNG ou JPEG) et la **taille** (originale, 2x, 0.5x, ...).
   - Cliquez sur **Télécharger l'image** pour obtenir votre labyrinthe au format et à la taille sélectionnés.

3. **Étape 3 : Revenir à la configuration**
   - Si vous souhaitez créer un autre labyrinthe, utilisez le bouton **Retour à la création du labyrinthe** pour revenir à la page de configuration initiale.

## Structure des fichiers

```plaintext
.
├── mazeCreator.html             # Page principale de configuration du labyrinthe
├── mazeGeneration.php           # Page pour exporter l'image du labyrinthe généré
├── mazeRender.php               # Page qui génère et affiche un labyrinthe en fonction des paramètres
├── mazeExport.php               # Page pour télécharger l'image avec format et redimensionnement
├── mazeFunction.php             # Fonctions de génération et d'affichage des labyrinthes
├── img/                         # Dossier contenant les banques de tuiles (images PNG)
│   ├── 2D_Maze_Tiles_Halloween.png
│   ├── 2D_Maze_Tiles_Red.png
│   └── 2D_Maze_Tiles_White.png
└── README.md                    # Documentation du projet
```

## Fonctionnement du code

### 1. Génération du labyrinthe

Le labyrinthe est généré par un **algorithme de fusion aléatoire** qui ouvre des murs entre cases voisines jusqu'à ce que toutes les cases soient connectées, garantissant un chemin unique entre toutes les cases (labyrinthe parfait).

### 2. Affichage avec des tuiles

L’affichage du labyrinthe est réalisé en utilisant une **banque de tuiles**. Les cases sont représentées par des images de tuiles sélectionnées selon la configuration de leurs murs (ouverts ou fermés) et sont tournées si nécessaire pour correspondre aux murs de chaque case.

### 3. Téléchargement et redimensionnement

Le téléchargement de l’image est géré par **mazeExport.php**, qui permet de choisir une taille et les formats PNG ou JPEG avant d'envoyer l'image au navigateur pour le téléchargement.

## Jeux d’essais

Les tests ont été réalisés pour s’assurer de la fiabilité et de la qualité de chaque fonctionnalité :
- Génération de labyrinthes de différentes tailles (5x5, 10x10, etc.) avec des graines aléatoires ou non.
- Vérification de l’affichage des murs des cases pour correspondre aux tuiles sélectionnées.
- Téléchargement dans différents formats et résolutions pour évaluer la qualité du rendu.

## Auteurs et contributions

**Auteurs** : LECOINTE Théo & FOURMY Damien
> **Reprises de codes pour le CSS:** [Coding2GO](https://www.youtube.com/watch?v=ezP4kbOvs_E)  |  [WebKitCoding](https://www.youtube.com/watch?v=Tdzas-IlKSM)  
> **Banque de tuile originale :** [itch.io](https://mapsandapps.itch.io/2d-maze-tiles)


