<?php


class Article
{
    public $id_article;
    public $titre;
    public $chapo;
    public $auteur;
    public $id_chapitre;
    public $attribut_complementaire;

    // exemple de constructeur qui corrige et complète des valeurs
    // la variable $this contient les données initialisées par fetchObject()
    function __construct()
    {
        // conversion d'un attribut
        $this->id_article = intval($this->id_article);

        // remplace une valeur vide par autre chose
        if (empty($this->titre)) $this->titre = 'inconnu';

        // définit un attribut complémentaire (hors base de données)
        $this->attribut_complementaire = 'valeur hors base de données';
    }

    function affichechapo()
    {
        echo '<p>' . $this->chapo . '</p>';
    }

    function affichetitre()
    {
        echo '<p>' . $this->titre . '</p>';
    }

    function chargePOST()
    {
        // On teste si la case 'titre' existe, si oui on copie sa valeur, sinon on utilise une valeur par défaut
        if (isset($_POST['id_article'])) {
            $this->id_article = $_POST['id_article'];
        } else {
            $this->id_article = 0;
        }
        /**/
        if (isset($_POST['titre'])) {
            $this->titre = $_POST['titre'];
        } else {
            $this->titre = 'sans titre';
        }
        // Idem pour le prétitre
        if (isset($_POST['chapo'])) {
            $this->chapo = $_POST['chapo'];
        } else {
            $this->chapo = '';
        }
        /**/
        if (isset($_POST['auteur'])) {
            $this->auteur = $_POST['auteur'];
        } else {
            $this->auteur = 'sans auteur';
        }
    }

    function affiche()
    {
        echo '<p>';
        echo $this->chapo . ' ' . $this->titre;
        // Lien pour voir le detail
        echo '<a href="controleur.php?page=article&id_article=' . $this->id_article . '"> voir le détail /</a>';
        // Lien pour supprimer
        echo '<a href="controleur.php?page=article&action=delete&id_article=' . $this->id_article . '"> supprimer /</a>';
        echo '<a href="controleur.php?page=article&action=modifier&id_article=' . $this->id_article . '"> modifier</a>';
        echo '</p>';
    }

    static function readAll()
    {
        // définition de la requête SQL
        $sql = 'select * from article ';

        // connexion
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // exécution de la requête
        $query->execute();

        // récupération de toutes les lignes sous forme d'objets
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Article');

        // retourne le tableau d'objets
        return $tableau;
    }

    static function readOne($id_article)
    {
        // définition de la requête SQL avec un paramètre :valeur
        $sql = 'select * from article where id_article = :valeur';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :valeur à la variable $id_article reçue
        $query->bindValue(':valeur', $id_article, PDO::PARAM_INT);

        // exécution de la requête
        $query->execute();

        // récupération de l'unique ligne
        $objet = $query->fetchObject('Article');

        // retourne l'objet contenant résultat
        return $objet;
    }


    function modifier($t, $te, $s, $c)
    {
        $this->titre = $t;
        $this->chapo = $te;
        $this->auteur = $s;
        $this->id_chapitre = $c;
    }

    function create()
    {
        // construction de la requête :titre, :chapo sont les valeurs à insérées
        $sql = 'INSERT INTO article (titre, chapo, auteur, id_chapitre) VALUES (:titre, :chapo, :auteur, :id_chapitre);';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();

        // on récupère la clé de l'article inséré
        $this->id_article = $pdo->lastInsertId();
    }

    static function delete($id_article)
    {
        // construction de la requête :titre, :chapo sont les valeurs à insérées
        $sql = 'DELETE FROM article WHERE id_article = :id_article;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :id_article à la variable $id_article reçue
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);

        // exécution de la requête
        $query->execute();
    }

    function update()
    {
        // construction de la requête :titre, :chapo sont les valeurs à insérées
        $sql = 'UPDATE article SET titre = :titre , chapo = :chapo, auteur = :auteur, id_chapitre = :id_chapitre WHERE id_article = :id_article;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
        $query->bindValue(':id_chapiter', $this->id_chapitre, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();
    }

    function afficheForm()
    {
        echo '
        <form action="controleur.php?page=article&action=update" method="post" class="row g-4 needs-validation" novalidate>
        <div class="col-md-4">
        <label for="id_article" class="form-label mt-4">id :</label>
        <input type="text" name="id_article" placeholder="Titre" value= "' . $this->id_article . '"
            class="form-control w-75 ms-5 border-black">
    </div>
    <div class="col-md-4">
        <label for="titre" class="form-label mt-4">Titre :</label>
        <input type="text" name="titre" placeholder="Titre" value= "' . $this->titre . '"
            class="form-control w-75 ms-5 border-black">
    </div>

    <div class="col-md-4">
        <label for="chapo" class="form-label mt-4">Chapô : </label>
        <input type="text" name="chapo" placeholder="Saisir le texte" value= "' . $this->chapo . '" class="form-control w-75 ms-5 border-black">
    </div>

    <div class="col-md-4">
        <label for="auteur" class="form-label mt-4">Auteur : </label>
        <input type="text" name="auteur" placeholder="Qui est l\'auteur ?" value= "' . $this->auteur . '"
            class="form-control w-75 ms-5 border-black">
    </div>
    
    <div class="col-md-4">
        <label for="id_chapitre" class="form-label mt-4">Auteur : </label>
        <input type="text" name="id_chapitre" placeholder="dans quel chapitre" value= "' . $this->id_chapitre . '"
            class="form-control w-75 ms-5 border-black">
    </div>

    <div class="d-flex justify-content-center">
        <button type="submit"
            class="submit border-light-subtle p-2 rounded-2 bg-secondary-subtle w-25 mt-2">Modifier</button>
    </div>
</form>';
    }
}
