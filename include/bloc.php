<?php


class Bloc
{
    public $id;
    public $type;
    public $texte;
    public $style;
    public $src;
    public $alt;
    public $id_article;

    // exemple de constructeur qui corrige et complète des valeurs
    // la variable $this contient les données initialisées par fetchObject()
    function __construct()
    {
        // conversion d'un attribut
        $this->id = intval($this->id);

        // remplace une valeur vide par autre chose
        if (empty($this->type)) $this->type = 'inconnu';
    }

    function affichetexte()
    {
        echo '<p>' . $this->texte . '</p>';
    }

    function affichetype()
    {
        echo '<p>' . $this->type . '</p>';
    }

    function chargePOST()
    {
        // On teste si la case 'type' existe, si oui on copie sa valeur, sinon on utilise une valeur par défaut
        if (isset($_POST['id'])) {
            $this->id = $_POST['id'];
        } else {
            $this->id = 0;
        }
        /**/
        if (isset($_POST['type'])) {
            $this->type = $_POST['type'];
        } else {
            $this->type = 'sans type';
        }
        // Idem pour le prétype
        if (isset($_POST['texte'])) {
            $this->texte = $_POST['texte'];
        } else {
            $this->texte = '';
        }
        /**/
        if (isset($_POST['style'])) {
            $this->style = $_POST['style'];
        } else {
            $this->style = 'sans style';
        }
        if (isset($_POST['src'])) {
            $this->src = $_POST['src'];
        } else {
            $this->src = 'image sans lien';
        }
        if (isset($_POST['alt'])) {
            $this->alt = $_POST['alt'];
        } else {
            $this->alt = 'image sans description';
        }
        if (isset($_POST['id_article'])) {
            $this->id_article = $_POST['id_article'];
        } else {
            $this->id_article = 'image sans description';
        }
    }

    function affiche()
    {
        echo '<p>';
        echo $this->texte . ' ' . $this->type;
        // Lien pour voir le detail
        echo '<a href="controleur.php?page=bloc&id=' . $this->id . '"> voir le détail /</a>';
        // Lien pour supprimer
        echo '<a href="controleur.php?page=bloc&action=delete&id=' . $this->id . '"> supprimer /</a>';
        echo '<a href="controleur.php?page=bloc&action=modifier&id=' . $this->id . '"> modifier</a>';
        echo '</p>';
    }

    static function readAll()
    {
        // définition de la requête SQL
        $sql = 'select * from bloc';

        // connexion
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // exécution de la requête
        $query->execute();

        // récupération de toutes les lignes sous forme d'objets
        $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');

        // retourne le tableau d'objets
        return $tableau;
    }

    static function readOne($id)
    {
        // définition de la requête SQL avec un paramètre :valeur
        $sql = 'select * from bloc where id = :valeur';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :valeur à la variable $id reçue
        $query->bindValue(':valeur', $id, PDO::PARAM_INT);

        // exécution de la requête
        $query->execute();

        // récupération de l'unique ligne
        $objet = $query->fetchObject('Bloc');

        // retourne l'objet contenant résultat
        return $objet;
    }

    static function readByArticle($id_article)
    {
        $sql = 'SELECT bloc.* FROM bloc INNER JOIN article on bloc.id_article = article.id_article WHERE article.id_article = :valeur';

        // Connexion à la base de données
        $pdo = connexion();

        // Préparation de la requête
        $query = $pdo->prepare($sql);

        // Lier le paramètre :valeur à la variable $id_article
        $query->bindValue(':valeur', $id_article, PDO::PARAM_INT);

        // Exécution de la requête
        $query->execute();

        // Récupération des résultats sous forme de tableau d'objets de type Bloc
        $bloc = $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');

        // Retourner le tableau contenant les blocs
        return $bloc;
    }


    function modifier($t, $te, $s, $src, $alt, $ida)
    {
        $this->type = $t;
        $this->texte = $te;
        $this->style = $s;
        $this->src = $src;
        $this->alt = $alt;
        $this->id_article = $ida;
    }

    function create()
    {
        // construction de la requête :type, :texte sont les valeurs à insérées
        $sql = 'INSERT INTO bloc (type, texte, style, src, alt, id_article) VALUES (:type, :texte, :style, :src, :alt, :id_article);';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':src', $this->src, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();

        // on récupère la clé de l'bloc inséré
        $this->id = $pdo->lastInsertId();
    }

    static function delete($id)
    {
        // construction de la requête :type, :texte sont les valeurs à insérées
        $sql = 'DELETE FROM bloc WHERE id = :id;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on lie le paramètre :id à la variable $id reçue
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        // exécution de la requête
        $query->execute();
    }

    function update()
    {
        // construction de la requête :type, :texte sont les valeurs à insérées
        $sql = 'UPDATE bloc SET type = :type , texte = :texte, style = :style, src = :src, alt = :alt, id_article = :id_article WHERE id = :id;';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':src', $this->src, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();
    }

    function afficheForm()
    {
        echo '
        <form action="controleur.php?page=bloc&action=update" method="post" class="row g-4 needs-validation">

    <div class="col-md-4">
        <label for="type" class="form-label mt-4">Type de bloc :</label>
        <input type="text" name="id" required="required" placeholder="Saisir un id" value= "' . $this->id . '"
            class="form-control w-75 ms-5 border-black">
    </div>
    <div class="col-md-4">
        <label for="type" class="form-label mt-4">Type de bloc :</label>
        <input type="text" name="type" required="required" placeholder="Quel type ?" value= "' . $this->type . '"
            class="form-control w-75 ms-5 border-black">
    </div>

    <div class="col-md-4">
        <label for="texte" class="form-label mt-4">Contenu :</label>
        <input type="text" name="texte" placeholder="Saisir le texte" class="form-control w-75 ms-5 border-black" value= "' . $this->texte . '">
    </div>

    <div class="col-md-4">
        <label for="style" class="form-label mt-4">Style :</label>
        <select id="style" name="style" class="form-select w-75 ms-5 border-black" aria-label="Default select example" value= "' . $this->style . '">
            <option value="aucun" selected>aucun</option>
            <option value="bleu">bleu</option>
            <option value="rouge">rouge</option>
            <option value="vert">vert</option>
            <option value="violet">violet</option>
            <option value="rose">rose</option>
            <option value="jaune">jaune</option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="src" class="form-label mt-4">Lien de l\'image : </label>
        <input type="text" name="src" placeholder="lien de l\'image (si type = image)"
            class="form-control w-75 ms-5 border-black" value= "' . $this->src . '">
    </div>

    <div class="col-md-4">
        <label for="alt" class="form-label mt-4">Description de l\'image : </label>
        <input type="text" name="alt" placeholder="description de l\'image (si type = image)"
            class="form-control w-75 ms-5 border-black" value= "' . $this->alt . '">
    </div>

    <div class="col-md-4">
        <label for="id_article" class="form-label mt-4">Article :</label>
        <input type="text" name="id_article" required="required" placeholder="Dans quel article apparait-il ?"
            class="form-control w-75 ms-5 border-black" value= "' . $this->id_article . '">
    </div>
    <div class="d-flex justify-content-center">
        <button type="submit"
            class="submit border-light-subtle p-2 rounded-2 bg-secondary-subtle w-25 mt-2">Modifier</button>
    </div>
</form>';
    }
}
