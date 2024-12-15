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
    public $audio;
    public $video;
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

    static function readAll()
    {
        $sql = 'SELECT * FROM bloc ORDER BY ordre';
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Bloc');
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

        $sql = "INSERT INTO bloc (ordre, type, texte, style, image, audio, video, id_article)
				VALUES (:ordre, :type, :texte, :style, :image, :audio, :video, :id_article)";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':audio', $this->audio, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
        $query->bindValue(':id_article', $this->id_article, PDO::PARAM_INT);
        $query->execute();
        $this->id = $pdo->lastInsertId();
    }

    function update()
    {
        $sql = "UPDATE bloc
				SET ordre=:ordre, type=:type, texte=:texte, style=:style, image=:image, audio=:audio, video:video
				WHERE id=:id";
        $pdo = connexion();
        $query = $pdo->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':ordre', $this->ordre, PDO::PARAM_INT);
        $query->bindValue(':type', $this->type, PDO::PARAM_STR);
        $query->bindValue(':texte', $this->texte, PDO::PARAM_STR);
        $query->bindValue(':style', $this->style, PDO::PARAM_STR);
        $query->bindValue(':image', $this->image, PDO::PARAM_STR);
        $query->bindValue(':audio', $this->audio, PDO::PARAM_STR);
        $query->bindValue(':video', $this->video, PDO::PARAM_STR);
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
        $this->audio = postString('old-audio');
        $this->video = postString('old-video');
        $this->id_article = postInt('id_article');

        // Récupère les informations sur le fichier uploadés si il existe
        $image = chargeFILE('image');
        if (!empty($image)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->image);
            $this->image = $image;
        }

        $audio = chargeFILE('audio');
        if (!empty($audio)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->audio);
            $this->audio = $audio;
        }

        $video = chargeFILE('video');
        if (!empty($video)) {
            // Supprime l'ancienne image si update
            unlink('upload/' . $this->video);
            $this->video = $video;
        }
    }


    static function controleur($action, $id, &$modele, &$data)
    {
        switch ($action) {
            default:
                $modele = 'bloc/liste_blocs.twig.html';
                $data = [
                    'bloc' => Bloc::readOne($id)
                ];
                break;
        }
    }

    static function controleurAdmin($action, $id, &$modele, &$data)
    {
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'bloc/bloc.twig.html';
                    $data = ['bloc' => Bloc::readOne($id)];
                } else {
                    $modele = 'bloc/liste_blocs.twig.html';
                    $data = ['listebloc' => Bloc::readAll()];
                }
                break;

            case 'new':
                $modele = 'form/' . $id . 'twig.html';
                $data = [];
                break;

            case 'create':
                $bloc = new Bloc();
                $bloc->chargePOST();
                $bloc->create(); // utilise maintenant la vraie variable $_POST
                header('Location: controleur.php?page=bloc&action=read');
                break;

            case 'delete':
                echo 'Suppression du bloc';
                Bloc::delete($id);
                header('Location: controleur.php?page=bloc&action=read');
                break;

            case 'modifier':
                $bloc = Bloc::readOne($id);
                $modele = 'bloc/updatebloc.twig.html';
                $data = [$bloc->afficheForm()];
                break;

            case 'update':
                $bloc = new Bloc();
                $bloc->chargePOST();    // utilise maintenant la vraie variable $_POST;
                $bloc->update();
                header('Location: controleur.php?page=bloc&action=read');
                break;

            default:
                echo 'Action non reconnue';

                break;
        }
    }
}
