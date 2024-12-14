<?php

require_once('fonctions.php');

class Chapitre
{
    public $id_chapitre;
    public $titre;
    public $chapo;
    public $image;

    // Le constructeur corrige les données récupérées de la BDD
    // Ici convertie l'identifiant en entier
    function __construct()
    {
        $this->id_chapitre = intval($this->id_chapitre);
    }

    static function readAll()
    {
        $sql = 'SELECT * FROM chapitre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Chapitre');
    }

    static function readOne($id_chapitre)
    {
        $sql = 'SELECT * FROM chapitre WHERE id_chapitre = :id_chapitre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $id_chapitre, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchObject('Chapitre');
    }

    function create()
    {
        $sql = "INSERT INTO chapitre (titre,chapo,image) VALUES (:titre, :chapo, :image)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->execute();
        $this->id_chapitre = $pdo->lastInsertId();
    }

    function update()
    {
        $sql = "UPDATE chapitre SET titre=:titre, chapo=:chapo, image=:image WHERE id_chapitre=:id_chapitre";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
        $query->bindValue(':chapo', $this->chapo, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->execute();
    }

    function delete()
    {
        // Suppression du fichier lié
        if (!empty($this->image)) unlink('upload/' . $this->image);

        // Suppression du thème
        $sql = "DELETE FROM chapitre WHERE id_chapitre=:id_chapitre";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_chapitre', $this->id_chapitre, PDO::PARAM_INT);
        $query->execute();
    }

    function chargePOST()
    {
        $this->id_chapitre = postInt('id_chapitre');
        $this->titre = postString('titre');
        $this->chapo = postString('chapo');
        $this->image = postString('old-image');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');
        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }
    }

    static function controleur($action, $id_chapitre, &$view, &$data)
    {
        switch ($action) {
            default:
                $view = 'visit_themes.twig';
                $data = ['liste_themes' => Chapitre::readAll()];
                break;
        }
    }

    static function controleurAdmin($action, $id_chapitre, &$view, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id_chapitre > 0) {
                    $view = 'chapitre/detail_theme.twig';
                    $data = [
                        'chapitre' => Chapitre::readOne($id_chapitre),
                        'liste_articles' => Article::readAllByTheme($id_chapitre)
                    ];
                } else {
                    $view = 'chapitre/liste_themes.twig';
                    $data = ['liste_themes' => Chapitre::readAll()];
                }
                break;
            case 'new':
                $view = "chapitre/form_theme.twig";
                break;
            case 'create':
                $chapitre = new Chapitre();
                $chapitre->chargePOST();
                $chapitre->create();
                header('Location: admin.php?page=chapitre');
                break;
            case 'edit':
                $view = "chapitre/edit_theme.twig";
                $data = ['chapitre' => Chapitre::readOne($id_chapitre)];
                break;
            case 'update':
                $chapitre = new Chapitre();
                $chapitre->chargePOST();
                $chapitre->update();
                header('Location: admin.php?page=chapitre');
                break;
            case 'delete':
                $chapitre = Chapitre::readOne($id_chapitre);
                $chapitre->delete();
                header('Location: admin.php?page=chapitre');
                break;
            default:
                $view = 'chapitre/liste_themes.twig';
                $data = ['liste_themes' => Chapitre::readAll()];
                break;
        }
    }

    // Création de la table themes
    static function init()
    {
        // connexion
        $pdo = connexion();

        // suppression des données existantes le cas échéant
        $sql = 'drop table if exists chapitre';
        $query = $pdo->prepare($sql);
        $query->execute();

        // création de la table 'chapitre'
        $sql = 'create table chapitre (
				id_chapitre serial primary key,
				titre varchar(128),
				chapo varchar(512),
				image varchar(512))';
        $query = $pdo->prepare($sql);
        $query->execute();
    }
}
