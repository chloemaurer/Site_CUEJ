<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once('fonctions.php');

class Article
{
    public $id_article;
    public $ordre;
    public $titre;
    public $auteur;
    public $chapo;
    public $image;
    public $alt;
    public $id_chapitre;

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie les clés et l'ordre (pour le tri) en entier
    function __construct()
    {
        $this->id_article = intval($this->id_article);
        $this->ordre = intval($this->ordre);
        $this->id_chapitre = intval($this->id_chapitre);
    }

    static function readAll()
    {
        $sql = 'SELECT * FROM article ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Article');
    }

    static function readOne($id_article)
    {
        $sql = 'SELECT * FROM article WHERE id_article = :id_article';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Article');
    }

    // Récupère les articles d'un thème
    static function readAllBychapitre($id_article)
    {
        $sql = 'SELECT * FROM article WHERE id_chapitre = :id_article ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Article');
    }

    // Récupère l'ordre maximal des articles d'un thème
    static function readOrderMax($id_article)
    {
        $sql = 'SELECT max(ordre) AS maximum FROM article WHERE id_chapitre = :id_article';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->execute();
        $objet = $query->fetchObject();
        return intval($objet->maximum);
    }

    // Echange l'ordre de deux articles
    function exchangeOrder()
    {
        // Recherche l'article précédent (dans le même thème)
        // C'est l'article le plus grand, parmi les articles d'ordre inférieur

        // étape 1 : cherche les articles du même thème ayant un ordre inférieur
        $sql = 'SELECT * FROM article
				WHERE id_chapitre = :id_chapitre AND ordre < :ordre ORDER BY ordre DESC';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->execute();

        // étape 2 : les articles sont triés par ordre décroissant
        // donc le premier article est le plus grand des plus petits, donc le précédent
        $before = $query->fetchObject('Article');

        // Si le précédent existe (l'article courant n'est pas le premier)
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
        // Récupère l'ordre maximum pour créer l'article en dernière position
        $maximum = self::readOrderMax($this->id_chapitre);
        $this->ordre = $maximum + 1;

        $sql = "INSERT INTO article (ordre, titre, chapo, auteur, image, alt, id_chapitre)
				VALUES (:ordre, :titre, :chapo, :auteur, :image, :alt, :id_chapitre)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->execute();
        $this->id_article = $pdo->lastInsertId();
    }

    function update()
    {
        $sql = "UPDATE article
				SET ordre=:ordre, titre=:titre, chapo=:chapo, auteur=:auteur, image=:image, alt=:alt
				WHERE id_article=:id_article";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':alt', $this->alt, PDO::PARAM_STR);
        $query->execute();
    }

    function delete()
    {
        // Suppression du fichier lié
        if (!empty($this->image)) unlink('upload/' . $this->image);

        // Suppression de l'article
        $sql = "DELETE FROM article WHERE id_article=:id_article";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->execute();
    }

    function chargePOST()
    {
        $this->id_article = postInt('id_article');
        $this->ordre = postInt('ordre');
        $this->titre = postString('titre');
        $this->auteur = postString('auteur');
        $this->chapo = postString('chapo');
        $this->image = postString('old-image');
        $this->image = postString('alt');
        $this->id_chapitre = postInt('id_chapitre');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');
        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }
    }


    static function controleur($action, $id_article, &$view, &$data)
    {
        switch ($action) {
            default:
                $view = 'article.twig.html';
                $data = [
                    'article' => Article::readOne($id_article)
                ];
                break;
        }
    }

    static function controleurAdmin($action, $id_article, &$view, &$data)
    {
        switch ($action) {
            case 'read':
                // Liste des articles d'un thème ($id_article)
                if ($id_article > 0) {
                    $view = 'article/article.twig.html';
                    $data = [
                        'article' => Article::readOne($id_article),
                        'listebloc' => Bloc::readByArticle($id_article)
                    ];
                } else {
                    // Pas de thème sélectionné => retour à l'accueil
                    header('Location: admin.php?page=chapitre');
                }
                break;
            case 'new':
                $view = "article/newarticle.twig.html";
                $data = ['id_chapitre' => $id_article];
                break;
            case 'create':
                $article = new Article();
                $article->chargePOST();
                $article->create();
                header('Location: admin.php?page=chapitre&id_article=' . $article->id_chapitre);
                break;
            case 'edit':
                $view = "article/updatearticle.twig.html";
                $data = ['article' => Article::readOne($id_article)];
                break;
            case 'update':
                $article = new Article();
                $article->chargePOST();
                $article->update();
                header('Location: admin.php?page=chapitre&id_article=' . $article->id_chapitre);
                break;
            case 'delete':
                $article = Article::readOne($id_article);
                $article->delete();
                header('Location: admin.php?page=chapitre&id_article=' . $article->id_chapitre);
                break;
            case 'exchange':
                $article = Article::readOne($id_article);
                $article->exchangeOrder();
                $view = 'chapitre/chapitre.twig.html';
                header('Location: admin.php?page=chapitre&id_article=' . $article->id_chapitre);
                break;
            default:
                $view = 'chapitre/liste_chapitres.twig.html';
                $data = [
                    'liste_chapitres' => Article::readAll()
                ];
                break;
        }
    }

    // Création de la table chapitres
    static function init()
    {
        // connexion
        $pdo = connexion();

        // suppression des données existantes le cas échéant
        $sql = 'drop table if exists article';
        $query = $pdo->prepare($sql);
        $query->execute();

        // création de la table 'chapitre'
        $sql = 'create table article (
				id_article serial primary key,
				ordre int,
				titre varchar(128),
				auteur varchar(512),
				chapo text,
				image varchar(512),
				id_chapitre bigint unsigned,
    			foreign key (id_chapitre) references chapitre(id_article))';
        $query = $pdo->prepare($sql);
        $query->execute();
    }
}
