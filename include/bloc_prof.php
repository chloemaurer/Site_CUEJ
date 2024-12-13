<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once('fonctions.php');

class Bloc
{
    public $id;
    public $ordre;
    public $type;
    public $texte;
    public $style;
    public $image;
    public $id_article;

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie les clés et l'ordre (pour le tri) en entier
    function __construct()
    {
        $this->id = intval($this->id);
        $this->ordre = intval($this->ordre);
        $this->id_article = intval($this->id_article);
    }

    static function readOne($id)
    {
        $sql = 'SELECT * FROM bloc WHERE id = :id';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Bloc');
    }

    // Récupère les elements d'un thème
    static function readAllByArticle($id)
    {
        $sql = 'SELECT * FROM bloc WHERE id_article = :id ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');
    }

    // Récupère l'ordre maximal des elements d'un thème
    static function readOrderMax($id)
    {
        $sql = 'SELECT max(ordre) AS maximum FROM bloc WHERE id_article = :id';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject();
        return intval($objet->maximum);
    }

    // Echange l'ordre de deux elements
    function exchangeOrder()
    {
        // Recherche l'bloc précédent (dans le même thème)
        // C'est l'bloc le plus grand, parmi les elements d'ordre inférieur

        // étape 1 : cherche les elements du même thème ayant un ordre inférieur
        $sql = 'SELECT * FROM bloc
				WHERE id_article = :id_article AND ordre < :ordre ORDER BY ordre DESC';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->execute();

        // étape 2 : les elements sont triés par ordre décroissant
        // donc le premier bloc est le plus grand des plus petits, donc le précédent
        $before = $query->fetchObject('Bloc');

        // Si le précédent existe (l'bloc courant n'est pas le premier)
        if ($before) {
            // Échange les valeurs d'ordre et enregistre dans la BDD
            $tmp = $this->ordre;
            $this->ordre = $before->ordre;
            $this->update();
            $before->ordre = $tmp;
            $before->update();
        }
    }

    function create()
    {
        // Récupère l'ordre maximum pour créer l'bloc en dernière position
        $maximum = self::readOrderMax($this->id_article);
        $this->ordre = $maximum + 1;

        $sql = "INSERT INTO bloc (ordre, type, texte, style, image, id_article)
				VALUES (:ordre, :type, :texte, :style, :image, :id_article)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    function update()
    {
        $sql = "UPDATE bloc
				SET ordre=:ordre, type=:type, texte=:texte, style=:style, image=:image
				WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->execute();
    }

    function delete()
    {
        // Suppression du fichier lié
        if (!empty($this->image)) unlink('upload/' . $this->image);

        $sql = "DELETE FROM bloc WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    function chargePOST()
    {
        $this->id = postInt('id');
        $this->ordre = postInt('ordre');
        $this->type = postString('type');
        $this->texte = postString('texte');
        $this->texte = postString('style');
        $this->image = postString('old-image');
        $this->id_article = postInt('id_article');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE();
        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }
    }


    static function controleur($action, $id, &$view, &$data)
    {
        switch ($action) {
            default:
                $view = 'visit_element.twig';
                $data = [
                    'bloc' => Bloc::readOne($id)
                ];
                break;
        }
    }

    static function controleurAdmin($action, $id, &$view, &$data)
    {
        switch ($action) {
            case 'read':
                // Pas d'affichage d'un élément seul => retour à l'article
                header('Location: admin.php?page=article&id=' . $id);
                break;
            case 'new':
                $view = "bloc/form_element.twig";
                $data = ['id_article' => $id];
                break;
            case 'create':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->create();
                header('Location: admin.php?page=article&id=' . $bloc->id_article);
                break;
            case 'edit':
                $view = "bloc/edit_element.twig";
                $data = ['bloc' => Bloc::readOne($id)];
                break;
            case 'update':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->update();
                header('Location: admin.php?page=article&id=' . $bloc->id_article);
                break;
            case 'delete':
                $bloc = Bloc::readOne($id);
                $bloc->delete();
                header('Location: admin.php?page=article&id=' . $bloc->id_article);
                break;
            case 'exchange':
                $bloc = Bloc::readOne($id);
                $bloc->exchangeOrder();
                header('Location: admin.php?page=article&id=' . $bloc->id_article);
                break;
            default:
                // Pas d'action connue => retour à la page article
                header('Location: admin.php?page=article&id=' . $id);
        }
    }

    // Création de la table themes
    static function init()
    {
        // connexion
        $pdo = connexion();

        // suppression des données existantes le cas échéant
        $sql = 'drop table if exists bloc';
        $query = $pdo->prepare($sql);
        $query->execute();

        // création de la table 'theme'
        $sql = 'create table bloc (
				id serial primary key,
				ordre int,
				type varchar(128),
				texte text,
				image varchar(512),
				id_article bigint unsigned,
    			foreign key (id_article) references article(id))';
        $query = $pdo->prepare($sql);
        $query->execute();
    }
}
