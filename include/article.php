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
    public $bloc = [];

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie les clés et l'ordre (pour le tri) en entier
    function __construct()
    {
        $this->id_article = intval($this->id_article);
        $this->ordre = intval($this->ordre);
        $this->id_chapitre = intval($this->id_chapitre);
    }

    function insecables()
    {
        if ($this->chapo) $this->chapo = insecables($this->chapo);
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
    static function readAllBychapitre($id_chapitre)
    {
        $sql = 'SELECT * FROM article WHERE id_chapitre = :id_chapitre ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
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


    public static function countAllInChapter($id_chapitre)
    {
        $sql = "SELECT COUNT(*) AS total 
            FROM article 
            WHERE id_chapitre = :id_chapitre";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
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
        $maximum = self::readOrderMax($this->id_chapitre);
        $this->ordre = $maximum + 1;

        $sql = "INSERT INTO article (ordre, titre, chapo, auteur, image, id_chapitre)
            VALUES (:ordre, :titre, :chapo, :auteur, :image, :id_chapitre)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);

        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':auteur', $this->auteur, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);

        // Gestion des erreurs SQL
        try {
            $query->execute();
            $this->id_article = $pdo->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            exit;
        }
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

    public static function delete($id_article)
    {
        $pdo = connexion();
        Bloc::deleteByArticle($id_article);
        $sql = "DELETE FROM article WHERE id_article = :id_article";

        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);

        try {
            $query->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
            exit;
        }
    }


    function chargePOST()
    {
        $this->id_article = postInt('id_article');
        $this->ordre = postInt('ordre');
        $this->titre = postString('titre');
        $this->auteur = postString('auteur');
        $this->chapo = postString('chapo');
        $this->image = postString('old-image');
        $this->alt = postString('alt');
        $this->id_chapitre = postInt('id_chapitre');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');
        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }
    }



    public static function getPreviousInChapter($id_article, $id_chapitre)
    {
        $sql = "SELECT id_article 
                FROM article 
                WHERE id_article < :id_article 
                  AND id_chapitre = :id_chapitre 
                ORDER BY id_article DESC 
                LIMIT 1";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer l'article suivant dans le même chapitre
    public static function getNextInChapter($id_article, $id_chapitre)
    {
        $sql = "SELECT id_article 
                FROM article 
                WHERE id_article > :id_article 
                  AND id_chapitre = :id_chapitre 
                ORDER BY id_article ASC 
                LIMIT 1";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article', $id_article, PDO::PARAM_INT);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    static function controleur($action, $id_article, &$modele, &$data)
    {
        switch ($action) {
            default:
                $modele = 'article/article_view.twig.html';

                // Lire l'article en cours
                $article = Article::readOne($id_article);

                // Lire tous les articles du chapitre
                $listearticleBychapitre = Article::readAllByChapitre($article->id_chapitre);

                // Trouver l'index de l'article actuel dans la liste des articles du chapitre
                $currentArticleIndex = array_search($id_article, array_column($listearticleBychapitre, 'id_article'));

                // S'assurer que les textes sont bien formatés avec les espaces insécables
                $article->insecables();

                // Récupérer tous les blocs associés à l'article
                $listebloc = Bloc::readAllByArticle($id_article);
                foreach ($listebloc as $bloc) {
                    $bloc->insecables();
                }

                // Définir les articles précédent et suivant dans le chapitre
                $previousArticle = null;
                $nextArticle = null;

                if ($currentArticleIndex > 0) {
                    $previousArticle = $listearticleBychapitre[$currentArticleIndex - 1];
                }

                if ($currentArticleIndex < count($listearticleBychapitre) - 1) {
                    $nextArticle = $listearticleBychapitre[$currentArticleIndex + 1];
                }

                // Définir les données à transmettre au template
                $data = [
                    'listearticleBychapitre' => $listearticleBychapitre,
                    'article' => $article,
                    'listebloc' => $listebloc,
                    'previousArticle' => $previousArticle,
                    'nextArticle' => $nextArticle,
                    'totalArticlesInChapter' => count($listearticleBychapitre), // Total d'articles
                    'currentArticleIndex' => $currentArticleIndex // Index actuel
                ];
                break;
        }
    }

    static function controleurAdmin($action, $id_article, &$modele, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id_article > 0) {
                    $modele = 'article/article.twig.html';
                    $data = [
                        'article' => Article::readOne($id_article),
                        'listebloc' => Bloc::readAllByArticle($id_article)
                    ];
                } else {
                    $modele = 'article/liste_articles.twig.html';
                    $data = ['listearticle' => Article::readAll()];
                }
                break;
                ////////////////////////////////////
            case 'new':
                $id_article = isset($_GET['id_article']) ? intval($_GET['id_article']) : 0;
                $modele = "article/newarticle.twig.html";
                $data = [
                    'id_article_selectionne' => $id_article,
                    'listechapitre' => Chapitre::readAll()
                ];
                break;



            case 'create':
                $article = new Article();
                $article->chargePOST();
                $article->create();
                header('Location: admin.php?page=article&action=read&id=' . $id_article);

                exit;
                break;
                ////////////////////////////////////

            case 'delete':
                // Récupère l'ID du chapitre avant de supprimer l'article
                $article = Article::readOne($id_article);
                $id_chapitre = $article->id_chapitre;

                // Supprime l'article
                Article::delete($id_article);

                // Redirige vers le chapitre correspondant
                header('Location: admin.php?page=chapitre&action=read&id=' . $id_chapitre);
                exit;
                break;

                ////////////////////////////////////
            case 'modifier':
                // Récupère l'ID de l'article depuis l'URL
                if ($id_article > 0) {
                    $article = Article::readOne($id_article);
                    $listechapitre = Chapitre::readAll(); // Pour le menu déroulant des chapitres


                    $article->titre = html_entity_decode($article->titre);
                    $article->chapo = html_entity_decode($article->chapo);
                    $article->auteur = html_entity_decode($article->auteur);

                    $modele = 'article/updatearticle.twig.html'; // Fichier du formulaire
                    $data = [
                        'article' => $article,
                        'listechapitre' => $listechapitre
                    ];
                } else {
                    header('Location: admin.php?page=article&action=read'); // Redirection si ID incorrect
                    exit;
                }
                break;

            case 'update':
                // Récupère les données du formulaire pour mettre à jour l'article
                $id = isset($_GET['id']) ? $_GET['id'] : null;

                $article = new Article();
                $article->chargePOST(); // Charge les nouvelles données
                $article->update();     // Met à jour dans la BDD

                header('Location: admin.php?page=chapitre&action=read&id=' . $article->id_chapitre); // Redirection vers la liste
                exit;
                break;


            case 'exchange':
                $article = Article::readOne($id_article);
                $article->exchangeOrder();
                header('Location: admin.php?page=chapitre&action=read&id=' . $article->id_chapitre);
                break;


            default:
                echo 'Action non reconnue';

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
